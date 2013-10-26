<?php
namespace Psdtg\AdminBundle\Admin\Helpdesk;

use Psdtg\AdminBundle\Admin\ActivateServiceRequestAdmin as BaseActivateServiceRequestAdmin;

class ActivateServiceRequestAdmin extends BaseActivateServiceRequestAdmin
{
    protected $baseRouteName = 'admin_lms_changeservicerequest_user';
    protected $baseRoutePattern = 'changeservicerequest_user';
}