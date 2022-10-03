# Contao Popup Article

Über javascript wird ein popup eines Artikels angezeigt 

## Frontend Route
Beispiele:

* /ajax_article/get_article/test
* /ajax_article/get_article/1

route: /ajax_article/get_article/{article}    article=id oder alias
                                              liefert json zurück
                                              
		return (['article' => $article,'id'=>$id,'alias' => $alias,'title' =>$title,'artikel'=>$art1])
        
        result Werte:
        
        $id      = Artikel ID
        $article = Parameter der Route
        $alias   = Alias des Artikels
        $title   = Titel des Artikels
        $art1    = html-code des Artikels
        

Es wird das js AjaxGetArticle(article) installiert.
  bundles/ajaxarticle/assets/js/ajaxhelper.js

Parameter: article (id oder alias) wird als Popup dargestellt.

z.B
&lt;button onclick="AjaxGetArticle('test');"&gt; klick&lt;/button&gt;
                                              


