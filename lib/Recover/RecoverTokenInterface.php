<?php
namespace ZealByte\Identity\Recover
{
	use ZealByte\Identity\Workflow\Subject\RsvpTrialSubjectInterface;

	interface RecoverTokenInterface extends RsvpTrialSubjectInterface
	{
		public function getUserId () : string;

		public function isExpired () : bool;

		public function isRevoked () : bool;
	}
}
