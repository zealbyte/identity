<?php
namespace ZealByte\Identity\Workflow\Subject
{
	interface RsvpTrialSubjectInterface
	{
		const STATUS_INITIALIZED = 'INITIALIZED';

		const STATUS_RSVP_PENDING = 'RSVP_PENDING';

		const STATUS_RSVP_SENT = 'RSVP_SENT';

		const STATUS_RSVP_RECEIVED = 'RSVP_RECEIVED';

		const STATUS_TRIAL_PROCEEDING = 'TRIAL_PROCEEDING';

		const STATUS_TRIAL_PASSED = 'TRIAL_PASSED';

		const STATUS_TRIAL_FAILED = 'TRIAL_FAILED';

		const STATUS_VERIFIED = 'VERIFIED';

		const STATUS_COMPLETED = 'COMPLETED';


		public function getStatus () : string;

		public function getKey () : string;

		public function getRsvp () : string;

		public function getTrial () : int;

		//public function setStatus (string $status) : RsvpTrialSubjectInterface;
	}
}
