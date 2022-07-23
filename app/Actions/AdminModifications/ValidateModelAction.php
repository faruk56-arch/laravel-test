<?php

namespace App\Actions\AdminModifications;

use App\Models\Product;
use App\Models\Research;
use App\Models\User;
use App\Notifications\AdminModifications\UserRefusedProductEdit;
use App\Notifications\AdminModifications\UserRefusedResearchEdit;
use Notification;
use Storage;

class ValidateModelAction
{
    /**
     * @var \App\Actions\AdminModifications\AdminEditable
     */
    private $model;

    public function __construct(AdminEditable $model)
    {
        $this->model = $model;
    }

    public static function accept(AdminEditable $model): void
    {
        $obj = new self($model);
        $modifications = $obj->model->modifications();
        if ($modifications->has('img')) {
            $obj->removeUnusedImages($modifications['img'], $obj->model->img);
        }
        $obj->saveChanges($modifications->toArray());
        $obj->model->removeMeta('modifications');
    }


    public static function refuse(AdminEditable $model): void
    {
        $obj = new self($model);
        $modifications = $obj->model->modifications();
        if ($modifications->has('img')) {
            $obj->removeUnusedImages($obj->model->img, $modifications['img']);
        }
        $obj->model->removeMeta('modifications');
        $obj->notifyAdmin();
    }

    public function removeUnusedImages(array $imgToKeep, array $imgToThrow): void
    {
        $unused = array_diff($imgToThrow, $imgToKeep);
        foreach ($unused as $image) {
            Storage::disk('public')
                ->delete(self::clearStorageFromString($image));
        }
    }

    private static function clearStorageFromString(string $url) {
        $prefix = '/storage';

        if (strpos($url, $prefix) === 0) {
            return substr($url, strlen($prefix));
        }
        return $url;
    }

    public function notifyAdmin(): void
    {
        $admins = User::where('role', 'admin')->get();
        if ($this->model instanceof Research) {
            Notification::send($admins,
                new UserRefusedResearchEdit($this->model));

            return;
        }
        if ($this->model instanceof Product) {
            Notification::send($admins,
                new UserRefusedProductEdit($this->model));
        }
    }

    private function saveChanges(array $changes): void
    {
        foreach ($changes as $key => $value) {
            $this->model[$key] = $value;
        }
        $this->model->saveWithoutEvents();
    }
}
