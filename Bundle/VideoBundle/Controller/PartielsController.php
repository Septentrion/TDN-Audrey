<?php

namespace TDN\Bundle\VideoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller {

    public function videosRecentesAction($limite, $panel = NULL) {

		$variables['activeParticipe'] = 'Propose une vidéo';
		$variables['typeEntite'] = 'video';
		$variables['titreEntite'] = 'Vidéos';
		$variables['messageEmpty'] = 'Aucun vidéo publiée sur TDN';
		$variables['lienSommaire'] = 'Toutes les vidéos';
		$variables['classeEntite'] = 'Video';

    	// Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
	    $textProcessor = $this->get('tdn.core.textprocessor');      

		// Récupération de la question la plus récente
		$repCauseuse = $em->getRepository('TDN\Bundle\VideoBundle\Entity\Video');
		$variables['recents'] = $repCauseuse->findMostRecent($limite, $panel);
		$variables['live'] = array_shift($variables['recents']);

		if (!empty($variables['live'])) {		
			$hebergeur = $variables['live']->getIdHebergeur();
			$abstract = $variables['live']->getAbstract();
			$variables['videoAbstract'] = $textProcessor->flattenAtSeparator($abstract, 120);
			$variables['videoAbstract'] .= $variables['videoAbstract'] === $abstract ? '' : ' (&hellip;&nbsp;suite)';
			switch ($hebergeur) {
				case 'dailymotion':
				case '2':
					$params = $variables['live']->getParams();
					$params = json_decode($params);
					$variables['codeIntegration'] = $variables['live']->getCodeIntegration();
					// $variables['codeIntegration'] = str_replace('480', '315', $variables['codeIntegration']);
					$variables['codeIntegration'] = preg_replace('/width="[0-9]+"/', 'width="360"', $variables['codeIntegration']);
					// $variables['codeIntegration'] = str_replace('360', '236', $variables['codeIntegration']);
					$variables['codeIntegration'] = preg_replace('/height="[0-9]+"/', 'height="203"', $variables['codeIntegration']);
					$minutes = floor($variables['live']->getDuree()/60);
					$secondes = $variables['live']->getDuree() - ($minutes * 60);
					$variables['duree'] = $minutes."' ".$secondes."\"";
					break;
				case 'vimeo':
				case '1':
					$ID = $variables['live']->getIdVideo();
					$variables['codeIntegration'] = "<iframe src='http://player.vimeo.com/video/$ID' width='360' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
					break;
				case 'youtube':
				case '0':
					$ID = $variables['live']->getIdVideo();
					$variables['codeIntegration'] = "<iframe width='360' height='270' src='https://www.youtube.com/embed/$ID?rel=0' frameborder='0' allowfullscreen></iframe>";
					break;
				default:
					$variables['codeIntegration'] = $variables['live']->getCodeIntegration();
			}
		}


        return $this->render('TDNVideoBundle:Partiels:videosRecentes.html.twig', $variables);
 
    }
}