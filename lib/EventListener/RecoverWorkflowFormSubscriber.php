<?php
namespace ZealByte\Identity\EventListener
{
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\Form\FormFactoryInterface;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	use Symfony\Component\Workflow\Registry;
	use Symfony\Component\Workflow\Event\GuardEvent;
	use ZealByte\Platform\ZealBytePlatform;
	use ZealByte\Platform\Context\ContextFactoryInterface;

	class RecoverWorkflowFormSubscriber extends IdentityFormSubscriberAbstract
	{
		const ON_START_RSVP_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_PREP_RSVP_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_SEND_RSVP_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_READ_RSVP_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_PREP_TRIAL_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_PASS_TRIAL_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_FAIL_TRIAL_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_VERIFY_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;

		const ON_COMPLETE_PRIORITY = ZealBytePlatform::REGULAR_PRIORITY;


		private $contextFactory;


		/**
		 * {@inheritdoc}
		 */
		public static function getSubscribedEvents ()
		{
			return [
				'workflow.rsvp_trial.guard.prep_rsvp' => [
					['onPrepRsvp', self::ON_PREP_RSVP_PRIORITY],
				],
				'workflow.rsvp_trial.guard.send_rsvp' => [
					['onSendRsvp', self::ON_SEND_RSVP_PRIORITY],
				],
				'workflow.rsvp_trial.guard.read_rsvp' => [
					['onReadRsvp', self::ON_READ_RSVP_PRIORITY],
				],
				'workflow.rsvp_trial.guard.prep_trial' => [
					['onPrepTrial', self::ON_PREP_TRIAL_PRIORITY],
				],
				'workflow.rsvp_trial.guard.pass_trial' => [
					['onPassTrial', self::ON_PASS_TRIAL_PRIORITY],
				],
				'workflow.rsvp_trial.guard.fail_trial' => [
					['onFailTrial', self::ON_FAIL_TRIAL_PRIORITY],
				],
				'workflow.rsvp_trial.guard.verify' => [
					['onVerify', self::ON_VERIFY_PRIORITY],
				],
				'workflow.rsvp_trial.guard.complete' => [
					['onComplete', self::ON_COMPLETE_PRIORITY],
				],
			];
		}

		public function __construct (ContextFactoryInterface $context_factory, FormFactoryInterface $form_factory, ?RequestStack $request_stack, ?UrlGeneratorInterface $url_generator = null)
		{
			$this->contextFactory = $context_factory;

			$this->setFormFactory($form_factory);

			if ($request_stack)
				$this->setRequestStack($request_stack);

			if ($url_generator)
				$this->setUrlGenerator($url_generator);
		}

		/**
		 *
		 */
		public function onPrepRsvp (GuardEvent $event) : void
		{
			$identityRecoverToken = $event->getSubject();
			$userId = $identityRecoverToken->getUserId();

			if (!$userId)
				$event->setBlocked(true);
		}

		/**
		 *
		 */
		public function onSendRsvp (GuardEvent $event) : void
		{
		}

		/**
		 *
		 */
		public function onReadRsvp (GuardEvent $event) : void
		{
		}

		/**
		 *
		 */
		public function onPrepTrial (GuardEvent $event) : void
		{
		}

		/**
		 *
		 */
		public function onPassTrial (GuardEvent $event) : void
		{
		}

		/**
		 *
		 */
		public function onFailTrial (GuardEvent $event) : void
		{
		}

		/**
		 *
		 */
		public function onVerify (GuardEvent $event) : void
		{
		}

		/**
		 *
		 */
		public function onComplete (GuardEvent $event) : void
		{
		}

	}
}
