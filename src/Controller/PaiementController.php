<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Event\CommentEvent;
use App\Entity\Event\Event;
use App\Entity\Event\GestionTicket;
use App\Entity\Event\Ticket;
use App\Entity\Home\GuestUser;
use App\Events;
use App\Repository\Event\ArtisteRepository;
use App\Repository\Event\CategorieAwardRepository;
use App\Repository\Event\CategorieEventRepository;
use App\Repository\Event\CommentEventRepository;
use App\Repository\Event\EventRepository;
use App\Repository\Event\GestionTicketRepository;
use App\Repository\Event\TicketRepository;
use App\Repository\Home\GuestUserRepository;
use App\Repository\Home\TagRepository;
use App\Repository\News\CategorieArticleRepository;
use App\Repository\PostRepository;
use App\Security\PostVoter;
use App\Utils\Slugger;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sonata\MediaBundle\Twig\Extension\MediaExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pay")
 * @author Junior MBOGNING <juniormbogning@gmail.com>
 */
class PaiementController extends AbstractFOSRestController {

    /**
     * @Route("/wecashup", methods={"POST"})
     */
    public function index(Request $request): Response {

      $merchant_uid = 'DIQuBxe30CdCV8tI13o6VjpEwf62';            
	$merchant_public_key = 'pk_test_JaMjtNl5Yf0N256U'; 
	$merchant_secret = 'sk_test_tnuXY372MHCwVD7vN0FOnFi5vmtBNVNJqbx3ztIp4zgP';    
        $transaction_uid = ''; // create an empty transaction_uid
        $transaction_token = ''; // create an empty transaction_token
        $transaction_provider_name = ''; // create an empty transaction_provider_name
        $transaction_confirmation_code = ''; // create an empty confirmation code

        $transaction_uid = $request->request->get('transaction_uid');
        $transaction_token = $request->request->get('transaction_token');
        $transaction_provider_name = $request->request->get('transaction_provider_name');
        $transaction_confirmation_code = $request->request->get('transaction_confirmation_code');
        $url = 'https://www.wecashup.com/api/v2.0/merchants/' . $merchant_uid . '/transactions/' . $transaction_uid . '?merchant_public_key=' . $merchant_public_key;
        //echo $url;
        //Steps 7 : You must complete this script at this to save the current transaction in your database.
        /* Provide a table with at least 5 columns in your database capturing the following
          /  transaction_uid | transaction_confirmation_code| transaction_token| transaction_provider_name | transaction_status */
        //Step 8 : Sending data to the WeCashUp Server


        $fields = array(
            'merchant_secret' => urlencode($merchant_secret),
            'transaction_token' => urlencode($transaction_token),
            'transaction_uid' => urlencode($transaction_uid),
            'transaction_confirmation_code' => urlencode($transaction_confirmation_code),
            'transaction_provider_name' => urlencode($transaction_provider_name),
            '_method' => urlencode('PATCH')
        );

        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }

        rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Step 9  : Retrieving the WeCashUp Response
        $server_output = curl_exec($ch);
        echo $server_output;
        curl_close($ch);
        $data = json_decode($server_output, true);

        $flashbag = $this->get('session')->getFlashBag();

        if ($data['response_status'] == "success") {
            // Add flash message
            $flashbag->add('info', 'votre paient success');
        } else {
            $flashbag->add('error', 'votre paient a echoue reessayez');
        }
        
        /*$session = $request->getSession();
        dump($data);
        die;
        
       $this->redirect($request->headers->get('referer'));*/
        
        //return $this->redirectToRoute('homepage');
        
        $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/failure.html';
	
	//redirect to your feedback page
	echo '<script>top.window.location = "'.$location.'"</script>';
        
    }

}
