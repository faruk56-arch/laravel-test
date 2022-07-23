<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AlertSubscription
 *
 * @property int $id
 * @property int|null $merchant_id
 * @property int|null $brand_id
 * @property int|null $modele_id
 * @property int|null $part_id
 * @property int|null $category_id
 * @property int|null $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Brand|null $brand
 * @property-read \App\Models\Category|null $category
 * @property-read mixed $translation
 * @property-read \App\Models\Merchant|null $merchant
 * @property-read \App\Models\Modele|null $modele
 * @property-read \App\Models\Part|null $part
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researches
 * @property-read int|null $researches_count
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription newQuery()
 * @method static \Illuminate\Database\Query\Builder|AlertSubscription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereModeleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlertSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AlertSubscription withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AlertSubscription withoutTrashed()
 */
	class AlertSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * Class BaseModel.
 *
 * @category Models
 * @author Thomas Tosch <thomas.tosch@wigomedia.com>
 * @property-read mixed $created_at
 * @property-read mixed $translation
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Query\Builder|BaseModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @method static \Illuminate\Database\Query\Builder|BaseModel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BaseModel withoutTrashed()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Brand
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modele[] $models
 * @property-read int|null $models_count
 * @method static \Illuminate\Database\Eloquent\Builder|Brand filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 */
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Category
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $hover
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modele[] $modeles
 * @property-read int|null $modeles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Part[] $parts
 * @property-read int|null $parts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Query\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereHover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property-read \App\Models\State $state
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Query\Builder|City onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Query\Builder|City withTrashed()
 * @method static \Illuminate\Database\Query\Builder|City withoutTrashed()
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Compatible
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id
 * @property int $modele_id
 * @property int $modele_compatible_id
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible query()
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible whereModeleCompatibleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible whereModeleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Compatible whereUpdatedAt($value)
 */
	class Compatible extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\State[] $states
 * @property-read int|null $states_count
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Query\Builder|Country onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Country withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Country withoutTrashed()
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Keyword
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Keyword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Keyword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Keyword query()
 */
	class Keyword extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Merchant
 *
 * @property int $id
 * @property string|null $merchant_name
 * @property string|null $merchant_address
 * @property string|null $merchant_city
 * @property string|null $merchant_zip
 * @property string|null $merchant_siret
 * @property string|null $merchant_type
 * @property int|null $country_id
 * @property int|null $currency
 * @property string|null $created at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \App\Models\Region|null $region
 * @property string|null $phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AlertSubscription[] $alerts
 * @property-read int|null $alerts_count
 * @property-read \App\Models\Country|null $country
 * @property-read mixed $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantSiret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereMerchantZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Merchant whereUpdatedAt($value)
 */
	class Merchant extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Message
 *
 * @property int $id
 * @property \App\Models\User|null $sender
 * @property \App\Models\User|null $recipient
 * @property string|null $type
 * @property int|null $type_id
 * @property int $template_id
 * @property array|null $params
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $checked
 * @property int $read
 * @property string|null $email
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read mixed $translation
 * @property-read \App\Models\MessageTemplate $template
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\MessageTemplate
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translation
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageTemplate whereUpdatedAt($value)
 */
	class MessageTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Modele
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $year_start
 * @property string|null $year_end
 * @property string|null $img
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $brand_id
 * @property-read \App\Models\Brand|null $brand
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Compatible[] $compatible
 * @property-read int|null $compatible_count
 * @property mixed $compatible_modeles
 * @property-read mixed $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $productsWithout
 * @property-read int|null $products_without_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Modele filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modele newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modele query()
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereYearEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modele whereYearStart($value)
 */
	class Modele extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Part
 *
 * @property int $id
 * @property int|null $category_id
 * @property int|null $modele_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AlertSubscription[] $alerts
 * @property-read int|null $alerts_count
 * @property-read \App\Models\Category|null $category
 * @property mixed $by_words
 * @property-read mixed $name
 * @property-read mixed $translation
 * @property-read \App\Models\Modele|null $modele
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $research
 * @property-read int|null $research_count
 * @method static \Illuminate\Database\Eloquent\Builder|Part filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Part newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Part newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Part query()
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereModeleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Part whereUpdatedAt($value)
 */
	class Part extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $merchant_id
 * @property float|null $price
 * @property float|null $minPrice
 * @property float|null $shipping_cost
 * @property float|null $shipping_cost_abroad
 * @property string|null $currency
 * @property string|null $description
 * @property string|null $manufacturer
 * @property string|null $suggestion
 * @property int $negotiable
 * @property int|null $stock
 * @property \App\Models\Status|null $status
 * @property string|null $reference
 * @property int|null $condition
 * @property string|null $img
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $part_id
 * @property string|null $type
 * @property int|null $delivery_option
 * @property float|null $weight
 * @property int $sent
 * @property string|null $intern
 * @property int|null $average_preparation_time
 * @property float|null $height
 * @property float|null $width
 * @property float|null $depth
 * @property int $public
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read mixed $img_with_index
 * @property-read mixed $original_description
 * @property-read mixed $original_language
 * @property-read mixed $original_name
 * @property-read mixed $translation
 * @property-read \App\Models\Merchant|null $merchant
 * @property-read \Illuminate\Database\Eloquent\Collection|\Plank\Metable\Meta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modele[] $modele
 * @property-read int|null $modele_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Part|null $part
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $research
 * @property-read int|null $research_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sale[] $sales
 * @property-read int|null $sales_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable orderByMeta(string $key, string $direction = 'asc', bool $strict = false)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable orderByMetaNumeric(string $key, string $direction = 'asc', bool $strict = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAveragePreparationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeliveryOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable whereDoesntHaveMeta($key)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable whereHasMeta($key)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable whereHasMetaKeys(array $keys)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIntern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable whereMeta(string $key, $operator, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable whereMetaIn(string $key, array $values)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminEditable whereMetaNumeric(string $key, string $operator, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNegotiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShippingCostAbroad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSuggestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidth($value)
 */
	class Product extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Region
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property int|null $country_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Country|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 */
	class Region extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Research
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $name
 * @property string|null $img
 * @property string|null $details
 * @property string|null $status
 * @property string|null $reference
 * @property array|null $unknown_part
 * @property string|null $compatible_suggestion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $part_id
 * @property int|null $modele_id
 * @property int $still
 * @property int|null $type
 * @property int $public
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AlertSubscription[] $alerts
 * @property-read int|null $alerts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read mixed $original_details
 * @property-read mixed $original_language
 * @property-read mixed $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Status[] $types
 * @property-read \Illuminate\Database\Eloquent\Collection|\Plank\Metable\Meta[] $meta
 * @property-read int|null $meta_count
 * @property-read \App\Models\UserModel|null $modele
 * @property-read \App\Models\Part|null $part
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SoldProduct[] $productMissed
 * @property-read int|null $product_missed_count
 * @property-read \App\Models\SoldProduct|null $productSold
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Sale[] $sales
 * @property-read int|null $sales_count
 * @property-read int|null $types_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Research filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Research newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Research newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Research orderByMeta(string $key, string $direction = 'asc', bool $strict = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Research orderByMetaNumeric(string $key, string $direction = 'asc', bool $strict = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Research query()
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereCompatibleSuggestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereDoesntHaveMeta($key)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereHasMeta($key)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereHasMetaKeys(array $keys)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereMeta(string $key, $operator, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereMetaIn(string $key, array $values)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereMetaNumeric(string $key, string $operator, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereModeleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereStill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUnknownPart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Research whereUserId($value)
 */
	class Research extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Sale
 *
 * @property int $id
 * @property int|null $research_id
 * @property int|null $product_id
 * @property string|null $status_preference
 * @property \App\Models\Status|null $status
 * @property string|null $status_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $translation
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Research|null $research
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereResearchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereStatusNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereStatusPreference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUpdatedAt($value)
 */
	class Sale extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SoldProduct
 *
 * @property int $id
 * @property int $old_id
 * @property string|null $name
 * @property int|null $merchant_id
 * @property float|null $price
 * @property float|null $minPrice
 * @property float|null $shipping_cost
 * @property float|null $shipping_cost_abroad
 * @property string|null $currency
 * @property string|null $description
 * @property string|null $manufacturer
 * @property string|null $suggestion
 * @property int $negotiable
 * @property int|null $stock
 * @property int|null $status
 * @property string|null $reference
 * @property int|null $condition
 * @property string|null $img
 * @property int|null $part_id
 * @property string|null $type
 * @property int|null $noDelivery
 * @property float|null $weight
 * @property int $sent
 * @property string|null $intern
 * @property int|null $average_preparation_time
 * @property float|null $height
 * @property float|null $width
 * @property float|null $depth
 * @property int|null $research_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $img_with_index
 * @property-read mixed $translation
 * @property-read \App\Models\Merchant|null $merchant
 * @property-read \App\Models\Research|null $research
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereAveragePreparationTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereIntern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereNegotiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereNoDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereOldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct wherePartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereResearchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereShippingCostAbroad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereSuggestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SoldProduct whereWidth($value)
 */
	class SoldProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\State
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Country $country
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Query\Builder|State onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static \Illuminate\Database\Query\Builder|State withTrashed()
 * @method static \Illuminate\Database\Query\Builder|State withoutTrashed()
 */
	class State extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Status
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $class
 * @property string|null $label
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translation
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUpdatedAt($value)
 */
	class Status extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Translation
 *
 * @property int $id
 * @property string|null $language
 * @property string|null $type nom de la table concernée
 * @property int|null $type_id id de l'element
 * @property string|null $key
 * @property string|null $fr_FR
 * @property string|null $en_EN
 * @property string|null $de_DE
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $bywords
 * @property-read mixed $translation
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereBywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereDeDE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereEnEN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereFrFR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translation whereUpdatedAt($value)
 */
	class Translation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $address
 * @property string|null $city
 * @property int|null $country_id
 * @property int|null $zip
 * @property int|null $merchant_id
 * @property int|null $merchant_role
 * @property string $email
 * @property string|null $obvy_email
 * @property string|null $obvy_pseudo
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $role
 * @property string|null $password_reset_token
 * @property string|null $lang
 * @property \App\Models\Region|null $region
 * @property string|null $phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Country|null $country
 * @property-read mixed $account_activated
 * @property-read mixed $alerts_match_view
 * @property-read mixed $alerts_view
 * @property-read mixed $matchs_view
 * @property-read mixed $products_view
 * @property-read mixed $researches_view
 * @property-read mixed $vehicles_view
 * @property-read \App\Models\Merchant|null $merchant
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messagesReceived
 * @property-read int|null $messages_received_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Plank\Metable\Meta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Modele[] $models
 * @property-read int|null $models_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researches
 * @property-read int|null $researches_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User orderByMeta(string $key, string $direction = 'asc', bool $strict = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User orderByMetaNumeric(string $key, string $direction = 'asc', bool $strict = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDoesntHaveMeta($key)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasMeta($key)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasMetaKeys(array $keys)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMerchantRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMeta(string $key, $operator, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMetaIn(string $key, array $values)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMetaNumeric(string $key, string $operator, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereObvyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereObvyPseudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordResetToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\UserModel
 *
 * @property int $id
 * @property int $user_id
 * @property int $modele_id
 * @property string|null $car_name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $year
 * @property string|null $version
 * @property-read mixed $translation
 * @property-read \App\Models\Modele $modele
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Research[] $researches
 * @property-read int|null $researches_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel filter(\Kyslik\LaravelFilterable\FilterContract $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereCarName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereModeleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereYear($value)
 */
	class UserModel extends \Eloquent {}
}

