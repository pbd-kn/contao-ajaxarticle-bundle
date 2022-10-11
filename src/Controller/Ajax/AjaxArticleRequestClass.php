<?php
// AjaxArticleRequestClass.php
declare(strict_types=1);

namespace PBDKN\AjaxArticleBundle\Controller\Ajax;

use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\InsertTag\InsertTagParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Contao\FilesModel;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception as DoctrineDBALDriverException;
use Doctrine\DBAL\Exception as DoctrineDBALException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;


class AjaxArticleRequestClass extends AbstractController
{
    private ContaoFramework $framework;
    private Connection $connection;
   /**
     * @var TranslatorInterface
     */
    private $translator;
    private InsertTagParser $insertTagParser;

        
    public function __construct(ContaoFramework $framework, Connection $connection,TranslatorInterface $translator,InsertTagParser $insertTagParser)
    {
        $this->framework = $framework;
        $this->connection = $connection;
        $this->translator = $translator;
        $this->insertTagParser = $insertTagParser;
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
	
        $strOutput =  $this->insertTagParser->replaceInline("{{insert_article::$alias}}");
        $pos = strpos($strOutput, "error");
        $art="";
        if($pos !== FALSE) {
          $invP=$this->translator->trans('MSC.invalidPage', [], 'contao_default');
          $invA=$this->translator->trans('CTE.article.0', [], 'contao_default');
          $invAl=$this->translator->trans('CTE.alias.0', [], 'contao_default');
          
		  $art = '<p class="error">' . sprintf($invP, $article) . '</p>';
          $art .= "<p>$invA: $article<br>$invAl(alias): $alias  </p>";
        } else {
          $art =  ltrim($strOutput);
        }
        $art = utf8_encode($art);         // json geht nur ordentlich mit utf-8
        
		$response = new JsonResponse(['article' => $article,'id'=>$id,'alias' => $alias,'title' =>$title,'artikel'=>$art]); 
		return $response; 

	}
}