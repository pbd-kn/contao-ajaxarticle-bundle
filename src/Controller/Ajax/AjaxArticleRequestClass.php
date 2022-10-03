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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
class AjaxArticleRequestClass
{
    private ContaoFramework $framework;
    private Connection $connection;
   /**
     * @var TranslatorInterface
     */
    private $translator;
        
    // constructor wird mittels  autowire: true in services.yaml gesetzt
    public function __construct(ContaoFramework $framework, Connection $connection,TranslatorInterface $translator)
    {
        $this->framework = $framework;
        $this->connection = $connection;
        $this->translator = $translator;
    }
    
    /**
     * @throws \Exception
     *
     * @Route("/ajax_article/get_article/{article}", 
     * name="AjaxArticleRequestClass::class\getArticle", 
     * defaults={"_scope" = "frontend"})
     */

	public function getArticleAjax(string $article)
	{
        global $GLOBALS;
        $this->framework->initialize(true);
        $objArticleModel = \ArticleModel::findByIdOrAliasAndPid($article, null);
        $id=$objArticleModel->id;
        $pid=$objArticleModel->pid;
		$title = $objArticleModel->title;    
        $alias = $objArticleModel->alias;
        $pObj = \PageModel::findByPk($pid);
        $GLOBALS['objPage'] = $pObj;                    // bei alten modules z.b.efg mit formulareingabe
	
		if (($strOutput = \Controller::getArticle($objArticleModel, false, true)) !== false){ 
          $art = ltrim($strOutput);
		} else {
            $trans=$this->translator->trans('MSC.invalidPage', [], 'contao_default');
			$art = '<p class="error">' . sprintf($trans, $article) . '</p>';
		}

        $art1 = str_replace(                 // Umlaute ersetzen
           ['ä','ö','ü','Ä','Ö','Ü','ß'],
           ['&auml;','&ouml;','&uuml;','&Auml;','&Ouml;','&Uuml;','&szlig;'],
           $art
        ); 
        //$art1=htmlspecialchars($art1);       
		return new JsonResponse(['article' => $article,'id'=>$id,'alias' => $alias,'title' =>$title,'artikel'=>$art1]); 

	}
}