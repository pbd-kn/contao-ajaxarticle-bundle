<?php
// AjaxArticleRequestClass.php
declare(strict_types=1);

namespace PBDKN\AjaxArticleBundle\Controller\Ajax;



use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DoctrineDBALDriverException;
use Doctrine\DBAL\Exception as DoctrineDBALException;
//use Markocupic\GalleryCreatorBundle\Model\GalleryCreatorAlbumsModel;
//use Markocupic\GalleryCreatorBundle\Model\GalleryCreatorPicturesModel;
//use Markocupic\GalleryCreatorBundle\Util\AlbumUtil;
//use Markocupic\GalleryCreatorBundle\Util\PictureUtil;
//use Markocupic\GalleryCreatorBundle\Util\SecurityUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AjaxArticleRequestClass 
{
    private ContaoFramework $framework;
    private Connection $connection;
    //private SecurityUtil $securityUtil;
    
    
    
    
/*   Hallo Marko, so sieht es in Deinem Controller beim Gallery Creator im File controller/GalleryCreatorAjax aus
    public function __construct(ContaoFramework $framework, Connection $connection, SecurityUtil $securityUtil, AlbumUtil $albumUtil, PictureUtil $pictureUtil)
    {
        $this->framework = $framework;
        $this->connection = $connection;
        //$this->securityUtil = $securityUtil;
    }
*/

/* Ich haette gerne 
   wenn ich den constructor weg lasse geht es einwandfrei, ich habe halt kein Contao Framework
   z.B. Kein Zugrif auf $GLOBALS
   Mit dem Construktor bekomme ich den Fehler
   
[2022-09-25T15:14:10.506337+00:00] request.INFO: Matched route "AjaxArticleRequestClass::class\getArticle". {"route":"AjaxArticleRequestClass::class\\getArticle","route_parameters":{"_route":"AjaxArticleRequestClass::class\\getArticle","_scope":"frontend","_controller":"PBDKN\\AjaxArticleBundle\\Controller\\Ajax\\AjaxArticleRequestClass::getArticleAjax","article":"1359"},"request_uri":"http://co4raw.local/ajax_article/get_article/1359","method":"GET"} []
[2022-09-25T15:14:10.576257+00:00] security.INFO: Populated the TokenStorage with an anonymous Token. [] []
[2022-09-25T15:14:10.593092+00:00] request.CRITICAL: Uncaught PHP Exception InvalidArgumentException: "The controller for URI "/ajax_article/get_article/1359" is not callable: Controller "PBDKN\AjaxArticleBundle\Controller\Ajax\AjaxArticleRequestClass" has required constructor arguments and does not exist in the container. Did you forget to define the controller as a service?" at C:\wampneu\www\co4\websites\co4raw\vendor\symfony\http-kernel\Controller\ControllerResolver.php line 88 {"exception":"[object] (InvalidArgumentException(code: 0): The controller for URI \"/ajax_article/get_article/1359\" is not callable: Controller \"PBDKN\\AjaxArticleBundle\\Controller\\Ajax\\AjaxArticleRequestClass\" has required constructor arguments and does not exist in the container. Did you forget to define the controller as a service? at C:\\wampneu\\www\\co4\\websites\\co4raw\\vendor\\symfony\\http-kernel\\Controller\\ControllerResolver.php:88)\n[previous exception] [object] (InvalidArgumentException(code: 0): Controller \"PBDKN\\AjaxArticleBundle\\Controller\\Ajax\\AjaxArticleRequestClass\" has required constructor arguments and does not exist in the container. Did you forget to define the controller as a service? at C:\\wampneu\\www\\co4\\websites\\co4raw\\vendor\\symfony\\http-kernel\\Controller\\ContainerControllerResolver.php:64)\n[previous exception] [object] (ArgumentCountError(code: 0): Too few arguments to function PBDKN\\AjaxArticleBundle\\Controller\\Ajax\\AjaxArticleRequestClass::__construct(), 0 passed in C:\\wampneu\\www\\co4\\websites\\co4raw\\vendor\\symfony\\http-kernel\\Controller\\ControllerResolver.php on line 147 and exactly 2 expected at C:\\wampneu\\www\\co4\\Github Bundles\\contao-ajaxarticle-bundle\\src\\Controller\\Ajax\\AjaxArticleRequestClass.php:41)"} []



    public function __construct(ContaoFramework $framework, Connection $connection)
    {
        $this->framework = $framework;
        $this->connection = $connection;
        //$this->securityUtil = $securityUtil;
    }
*/    
    /**
     * @throws \Exception
     *
     * @Route("/ajax_article/get_article/{article}", 
     * name="AjaxArticleRequestClass::class\getArticle", 
     * defaults={"_scope" = "frontend"})
     */

	public function getArticleAjax(string $article)
	{
// hier haette ich dann gerne das gesamte Contao-Frameworek, so wie du es auch hast
        //$this->framework->initialize(true);
        $objArticleModel = \ArticleModel::findByIdOrAliasAndPid($article, null);
        //$objArticleModel = \ArticleModel::findOneBy('alias',$article);
        $id=$objArticleModel->id;
        $pid=$objArticleModel->pid;
		$title = $objArticleModel->title;    
        $alias = $objArticleModel->alias;
        $pObj = \PageModel::findByPk($pid);
        $GLOBALS['objPage'] = $pObj;
//		return new JsonResponse(['id'=>$id,'pageId' => $pObj->id,'article' => $article,'alias' => $alias,'title' =>$title]); 
	
\System::log('PBD AjaxArticleRequestClass getArticle '.$article.' Titel '.$title, __METHOD__, TL_GENERAL);
		if (($strOutput = \Controller::getArticle($objArticleModel, false, true)) !== false){ 
          $art = ltrim($strOutput);
		} else {
			$art = '<p class="error">' . sprintf($GLOBALS['TL_LANG']['MSC']['invalidPage'], $elements[1]) . '</p>';
		}
		return new JsonResponse(['id'=>$id,'pageId' => $pObj->id,'article' => $article,'alias' => $alias,'title' =>$title,'artikel'=>$art]); 

	}
}