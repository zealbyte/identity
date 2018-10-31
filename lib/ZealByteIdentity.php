<?php
namespace ZealByte\Identity
{
	class ZealByteIdentity
	{
		const ROUTE_LOGIN = 'identity.route.secure_login';

		const ROUTE_MESSAGE_DISABLED = 'identity.route.secure_disabled';

		const ROUTE_ACCOUNT = 'identity.route.account';

		const ROUTE_LOGOUT = 'identity.route.logout';

		const ROUTE_RECOVER = 'identity.route.request';

		const ROUTE_SELF = 'identity.route.self';

		const ROUTE_USER = 'identity.route.user';

		const ROUTE_REGISTER = 'identity.route.register';

		const EVENT_LOGIN_FORM = 'identity.event.login_form';

		const EVENT_RECOVER_FORM = 'identity.event.recover_form';

		const EVENT_RECOVER_PROCESS_FORM = 'identity.event.recover_process_form';

	}
}
