<?php

// Dans le fichier /src/MonPath/MonBundle/Listener/ControllerListener

namespace MyEvents\MainBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener {

	public function onCoreController(FilterControllerEvent $event) {
		if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
			$_controller = $event->getController();
			if (isset($_controller[0])) {
				$controller = $_controller[0];
				if (method_exists($controller, 'preExecute')) {
					$controller->preExecute();
				}
			}
		}
	}

}
