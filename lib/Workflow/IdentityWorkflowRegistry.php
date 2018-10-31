<?php
namespace ZealByte\Identity\Workflow
{
	use Symfony\Component\Workflow\DefinitionBuilder;
	use Symfony\Component\Workflow\Transition;
	use Symfony\Component\Workflow\Workflow;
	use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;

	use Symfony\Component\Workflow\Registry;
	use Symfony\Component\Workflow\WorkflowInterface\InstanceOfSupportStrategy;

	use ZealByte\Identity\Entity\IdentityRecoverToken as Token;

	class IdentityWorkflowRegistry extends Registry
	{

		public function fony ()
		{
			//-----------------------------------
			$registry = new Registry();
			$registry->addWorkflow($rsvpTrialWorkflow, new InstanceOfSupportStrategy(Token::class));

			return $registry;
		}
	}
}
