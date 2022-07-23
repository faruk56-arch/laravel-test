<template>
    <div class="obvy-connection container">
		<div class="row">
	    	<div class="col-12 py-5 mt-3 text-center">
	    		<h1 class="text-center text-blue h2">Paiement sécurisé avec Obvy</h1>
	    		<a href="https://app.obvy-app.com/registration/identity" target="_blank" class="btn btn-lg btn-obvy">Créer mon compte <strong>Obvy</strong></a>
	    		<p class="pt-3" v-if="!$auth.check()"><strong>Déjà un compte Obvy ? </strong>
                    <a v-if="$i18n.locale=='fr'" href="https://classic-parts-finder.com/vendre-des-pieces/" class="basic text-bold-dark-grey">Créez votre compte Classic Parts Finder !</a>
                    <a v-else-if="$i18n.locale=='en'" :href="'https://classic-parts-finder.com/' + $i18n.locale + '/sell-parts'" class="basic text-bold-dark-grey">Créez votre compte Classic Parts Finder !</a>
                    <a v-else :href="'https://classic-parts-finder.com/' + $i18n.locale + '/teile-verkaufen'" class="basic text-bold-dark-grey">Créez votre compte Classic Parts Finder !</a>

                </p>
	    		<p class="pt-3" v-else-if="$auth.user().merchant_id"><strong>Déjà un compte Obvy ? </strong><router-link class="basic text-bold-dark-grey" :to="{name:'MerchantConfirmation'}">Ajoutez des produits dès maintenant!</router-link></p>
			</div>

			<div class="col-md-8 offset-md-2 bg-white rounded shadow px-5 pt-5 pb-4">
				<h3 class="text-center text-blue">Pourquoi vous faut-il un compte Obvy ?</h3>
				<div class="d-flex justify-content-center align-items-center py-4">
					<img class="px-4 logo-cpf" src="/images/logo/logo_classic_parts_finder_gold.svg">
					<p class="h3">+</p>
					<img class="px-4" src="/images/logo-obvy.png">
					<p class="h3">=</p>
					<img class="px-3" src="/images/picto/picto-zen.png">
				</div>
				<p class="lead font-weight-normal">Nous avons choisi Obvy, entreprise française spécialisée dans la sécurisation des transactions entre particuliers afin de garantir vos ventes en toute sécurité.</p>
				<ul class="list-unstyled">
					<li class="row py-4">
						<div class="col-md-1 h2 text-blue text-center">
							<i class="fal fa-wallet text-obvy"></i>
						</div>
						<div class="col-md-10">
							<h4>Solvabilité garantie pour le vendeur</h4>
							<p>Si l'acheteur accepte votre proposition, son argent est sécurisé sur un compte inviolable. Pour un envoi, l’argent est sécurisé durant toute la durée de la livraison.</p>
						</div>
					</li>
					<li class="row py-4">
						<div class="col-md-1 h2 text-blue text-center">
							<i class="fal fa-shield-check text-obvy"></i>
						</div>
						<div class="col-md-10">
							<h4>Protection contre les arnaques</h4>
							<p>Chaque action est analysée et croisée avec les profils initiateurs. Les utilisateurs suspects sont donc identifiés et traités de manière à ce qu’ils ne puissent pas devenir une nuisance pour les autres utilisateurs.</p>
						</div>
					</li>
					<li class="row py-4">
						<div class="col-md-1 h2 text-blue text-center">
							<i class="fal fa-credit-card text-obvy"></i>
						</div>
						<div class="col-md-10">
							<h4>Simplicité de paiement en ligne</h4>
							<p>Obvy permet à vos acheteurs de payer par CB ou par Virement. Dans le cas d’une remise en main propre de votre produit, l’acheteur pourra directement payer depuis son smartphone en scannant le QR Code Obvy. Vous recevez directement votre argent !</p>
						</div>
					</li>
					<li class="row pt-4">
						<div class="col-md-1 h2 text-blue text-center">
							<i class="fal fa-id-card text-obvy"></i>
						</div>
						<div class="col-md-10">
							<h4>Vérification d’identité</h4>
							<p>Acheteurs et vendeurs sont invités à vérifier leur compte afin d'obtenir un badge de confiance.</p>
						</div>
					</li>
				</ul>
				<h4 class="text-blue text-center py-3">Prêt à sécuriser vos ventes ?</h4>
				<div class="text-center">
					<a href="https://app.obvy-app.com/registration/identity" target="_blank" class="btn btn-lg btn-obvy">Créez votre compte <strong>Obvy</strong></a>
				</div>
				<div class="d-flex justify-content-center align-items-center pt-5">
					<a href="https://www.obvy-app.com/" target="_blank" class="basic text-blue">En savoir plus sur</a>
					<img class="little-obvy-logo pl-2" src="/images/logo-obvy.png">
				</div>
			</div>

        <!-- <router-link :to="{name:'MerchantConfirmation'}">Suivant</router-link> -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "ObvyConnection",
        created(){
            document.location.href = "#top";
        }
    }
</script>

<style scoped>

</style>
