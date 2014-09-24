{% extends 'DocumentBundle:Default:TDNPage.html.twig' %}

{% block title feuille.titre ~ " | Article " ~ titreRubrique  %}

{% block contenu_principal %}
<section id="" class="enveloppe-contenu">
     <h1 class="titre-document titre-{{ rubriquePrincipale(rubriquePrincipale) }}">{{feuille.titre}}</h1>

</section>
  <!-- Contenu de l'article -->
   <section class="contenu main-section">

		{% block corps_contenu %}{% block %}

       <!-- Bloc pour les boutons sociaux -->
      {% include 'DocumentBundle:Bloc:socialTDNLinks.html.twig' with {'id': feuille.idDocument, 'likes': feuille.likes} %}

       <!-- Mediabong -->
      <div id="mb_container"></div>
      <div id="mb_video_sponso"></div>
      <script type="text/javascript">
          (function(){
                  var sc = document.createElement('script');
                  sc.src = 'http://player.mediabong.com/se/793.js?url='+encodeURIComponent(document.location.href);
                  sc.type = 'text/javascript';
                  sc.async = true;
                  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sc, s);
          })();
      </script>

      <!-- Bloc pour les commentaires -->
      {% render 'CommentaireBundle:Public:flux' with {'id': feuille.idDocument } %}
  </section>

  <!-- Publications en rapport avec ce sujet -->
  {% if similaires is defined and similaires is not empty %}
  <section id="more">
    {% include 'DocumentBundle:Bloc:documentsSimilaires.html.twig' with {'similaires': similaires, 'type': 'Article'} %}
  </section>
  {% endif %}

</article>
<script type="text/javascript">
  $(document).ready(function() {
    console.log('Traitement des URL images');
    $('#article-wrapper img').each(function() {
      var source = $(this).attr('src');
      var radix = source.substr(0,7);
      if (radix == '/photos') {
        $(this).attr('src', 'http://www.trucsdenana.com'+source);
      }
    })
  })
</script>
{% endblock %}