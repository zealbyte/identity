<?php
namespace ZealByte\Identity\Workflow\Builder
{
	use Symfony\Component\Workflow\DefinitionBuilder;
	use Symfony\Component\Workflow\Transition;
	use Symfony\Component\Workflow\Workflow;
	use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;

	use Symfony\Component\Workflow\Registry;
	use Symfony\Component\Workflow\WorkflowInterface\InstanceOfSupportStrategy;

	use ZealByte\Identity\Entity\IdentityRecoverToken as Token;

	class RsvpTrialBuilder
	{
		public function build () : Workflow
		{
			$marking = new SingleStateMarkingStore('status');
			$builder = new DefinitionBuilder();

			$this->buildAddPlaces($builder);
			$this->buildAddTransitions($builder);

			$definition = $builder->build();

			return new Workflow($definition, $marking);
		}

		private function buildAddPlaces (DefinitionBuilder $builder) : void
		{
			$builder
				->addPlaces([
					Token::STATE_INITIALIZED,
					Token::STATE_RSVP_PENDING,
					Token::STATE_RSVP_SENT,
					Token::STATE_RSVP_RECEIVED,
					Token::STATE_TRIAL_PROCEEDING,
					Token::STATE_TRIAL_PASSED,
					Token::STATE_TRIAL_FAILED,
					Token::STATE_VERIFIED,
					Token::STATE_REVOKED,
					Token::STATE_COMPLETE,
				]);
		}

		private function buildAddTransitions (DefinitionBuilder $builder) : void
		{
			$builder
				->addTransition(new Transition('prep_rsvp', Token::STATE_INITIALIZED, Token::STATE_RSVP_PENDING))
				->addTransition(new Transition('send_rsvp', Token::STATE_RSVP_PENDING, Token::STATE_RSVP_SENT))
				->addTransition(new Transition('read_rsvp', Token::STATE_RSVP_SENT, Token::STATE_RSVP_RECEIVED))
				->addTransition(new Transition('prep_trial', Token::STATE_RSVP_RECEIVED, Token::STATE_TRIAL_PROCEEDING))
				->addTransition(new Transition('pass_trial', Token::STATE_TRIAL_PROCEEDING, Token::STATE_TRIAL_PASSED))
				->addTransition(new Transition('fail_trial', Token::STATE_TRIAL_PROCEEDING, Token::STATE_TRIAL_FAILED))
				->addTransition(new Transition('verify', Token::STATE_TRIAL_PASSED, Token::STATE_VERIFIED))
				->addTransition(new Transition('complete', Token::STATE_VERIFIED, Token::STATE_COMPLETED))
				->addTransition(new Transition('revoke', [
					Token::STATE_INITIALIZED,
					Token::STATE_RSVP_PENDING,
					Token::STATE_RSVP_SENT,
					Token::STATE_RSVP_RECEIVED,
					Token::STATE_TRIAL_PROCEEDING,
					Token::STATE_TRIAL_PASSED,
				], Token::STATE_REVOKED));
		}

	}
}
