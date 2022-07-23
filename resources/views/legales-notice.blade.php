
@include('header', ['mainTitle' => "Mentions légales"])

    <section class="container pb-5 mb-5">
        <div class="row">
            <div class="col-md-10 offset-md-1 ">

                <p>Conformément aux dispositions de la loi n° 2004-575 du 21 juin 2004 pour la confiance en l'économie numérique, il est précisé aux utilisateurs du Site l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi.</p>

                <h2 class="h4 text-blue mt-4">Editeur du site</h2>
                <p>Le site <a class="text-gold" href="https://www.classic-parts-finder.com">www.classic-parts-finder.com</a> est édité par la société Classic Parts Finder SAS au capital de 1 000 euros, immatriculée au Registre de Commerce et des Sociétés de Paris sous le n° 887 957 652 et ayant son siège social au 149 Avenue du Maine, 75014 Paris. N° de TVA intracommunautaire : FR 89887957652</p>

                <h2 class="h4 text-blue mt-4">Téléphone et Email de contact</h2>
                <p class="mb-1">Téléphone : <img src="/images/phone.svg" class=" mt-n1"></p>
                <p>Email : <img src="/images/email.svg" class=" mt-n1"></p>

                <p>Nous contacter en cliquant <a class="text-gold" href="/contact">ici</a></p>

                <h2 class="h4 text-blue mt-4">Responsable de la publication</h2>
                <p>Monsieur Benjamin Grappin, Président</p>

                <h2 class="h4 text-blue mt-4">Hébergeur</h2>
                <p>Le site <a class="text-gold" href="https://www.classic-parts-finder.com">www.classic-parts-finder.com</a> est hébergé par la société Infomaniak Network SA, dont le siège social est situé au 26, Avenue de la Praille – 1227 Carouge / Genève (CH). <a class="text-gold" href="https://www.infomaniak.com">https://www.infomaniak.com</a></p>

                <h2 class="h4 text-blue mt-4">Conditions générales</h2>
                <p>Ces dernières sont consultables <a class="text-gold" href="/{{ app()->getLocale() }}/conditions-generales-d-utilisation">ici</a></p>

                <h2 class="h4 text-blue mt-4">Politique de confidentialité</h2>
                <p>Ces dernières sont consultables <a class="text-gold" href="/{{ app()->getLocale() }}/conditions-generales-d-utilisation/#privacy-policy">ici</a></p>

                <h2 class="h4 text-blue mt-4">Remerciements</h2>
                <p>L’équipe Wigo Media, Thierry Sée, ainsi que Thierry Burkard.</p>

            </div>
        </div>
    </section>

@include('footer')

