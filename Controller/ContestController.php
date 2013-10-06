<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rodger
 * Date: 9/23/13
 * Time: 9:22 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Rodgermd\ContestNominationsBundle\Controller;

use Rodgermd\ContestNominationsBundle\Entity\ContestRepository;
use Rodgermd\ContestNominationsBundle\Manager\ContestManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContestController
 * @package Site\BaseBundle\Controller
 */
class ContestController extends Controller
{

  /**
   * Gets Contest repository
   * @return ContestRepository
   */
  protected function getRepository()
  {
    return $this->getDoctrine()->getRepository('SiteBaseBundle:Contest');
  }

  /**
   * Gets contest manager
   * @return ContestManager
   */
  protected function getManager()
  {
    return $this->get('rodgermd.contest_nominations.manager.contest');
  }
}