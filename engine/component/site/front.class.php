<?php
/**
 * Generic front class
 */
class front
{
	/**
	 * Constructor
	 *
	 * @access: public
	 * @return: null
	*/
	function front($action="")
	{
		switch($action)
	  	{
            case "home":
                $this->home();
            break;

            case "portofoliu":
                $this->portofoliu();
            break;

            case "portofoliu_single":
                $this->portofoliu_single();
            break;

            case "contact":
                $this->contact();
            break;

            case "process_contact":
                $this->process_contact();
            break;

            case "servicii":
                $this->servicii();
            break;

            case "despre":
                $this->despre();
            break;

            case "sitemap":
                $this->sitemap();
            break;

            case "error_404":
                $this->error_404();
            break;
	  	}
	}
		
	/**
	 * Home Page
	 *
	 * @access: public
	 * @return: null
	*/
	function home()
	{
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $smarty = $app->getTemplate();
        $db=SDatabase::getInstance();
        $lang = SLanguage::getInstance();
        objInitVar($this, "site/home.tpl", "home", "home", "", "", "");

        if($smarty->isCached($this->tplName, CACHE_ID)) {
            $smarty->display($this->tplName, CACHE_ID);
            return;
        }

        //set Spoof ID
        $_SESSION[SESS_IDX]['spoofID']=time().rand(0,100).md5(getIP());
        $smarty->assign("spoofID", $_SESSION[SESS_IDX]['spoofID']);
        $doc->setPageTitle("Pure Mess Design");

        //get slides
        $db->setQuery("SELECT * FROM slides WHERE status = 1 ORDER BY ordine");
        $slides = $db->loadObjectList();
        $smarty->assign("slides", $slides);

        $doc->setPageTitle("Pure Mess Design");
        $doc->setMetaTag("description", "Pure Mess Design");

        $smarty->display($this->tplName, CACHE_ID);
        $_SESSION[SESS_IDX]["legal_display"] = 1;
	}

    /**
     * Portofolio page
     *
     * @access: public
     * @return: null
     */
    function portofoliu() {
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $smarty = $app->getTemplate();
        $db=SDatabase::getInstance();
        $lang = SLanguage::getInstance();
        objInitVar($this, "site/portofoliu.tpl", "portofoliu", "portofoliu", "", "", "");

        // Get projects
        $db->setQuery("SELECT *, p.id as project_id, 'project' as main_type FROM projects p LEFT JOIN project_category pc ON p.category_id = pc.id WHERE p.status = 1 AND pc.status = 1 AND p.lang = ".$lang->lang);
        $proiecte = $db->loadObjectList();

        //Get subprojects
        $db->setQuery("SELECT *, sp.id as project_id, 'subproject' as main_type FROM subprojects sp LEFT JOIN project_category pc ON sp.category_id = pc.id WHERE sp.status = 1 AND pc.status = 1 AND sp.lang = ".$lang->lang);
        $subprojects = $db->loadObjectList();

        $projects = array_merge($proiecte, $subprojects);
        shuffle($projects);
        $smarty->assign("projects", $projects);

        // Get project categories
        $db->setQuery("SELECT * FROM project_category pc WHERE pc.status = 1 AND pc.lang = ".$lang->lang);
        $categories = $db->loadObjectList();
        $smarty->assign("categories", $categories);

        $doc->setPageTitle("Pure Mess Design");
        $doc->setMetaTag("description", "Pure Mess Design");

        $smarty->display($this->tplName);
    }

    /**
     * Portofolio Single page
     *
     * @access: public
     * @return: null
     */
    function portofoliu_single() {
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $smarty = $app->getTemplate();
        $db=SDatabase::getInstance();
        $lang = SLanguage::getInstance();
        objInitVar($this, "site/portofoliu_single.tpl", "portofoliu_single", "portofoliu_single", "", "", "");

        if($_GET['tip']=='subproiect') {
            // Get project details
            $id = $db->getEscaped($_GET['id']);
            $db->setQuery("SELECT * FROM subprojects p LEFT JOIN project_category pc ON p.category_id = pc.id WHERE p.id = $id AND p.lang = ".$lang->lang);
            $project = $db->loadObject();
            $smarty->assign("project", $project);

            // Get project pictures
            $poze = SUPLPhotoHelper::get_pictures("subprojects", $id);
            $smarty->assign("poze", $poze);
        } else {
            // Get project details
            $id = $db->getEscaped($_GET['id']);
            $db->setQuery("SELECT * FROM projects p LEFT JOIN project_category pc ON p.category_id = pc.id WHERE p.id = $id AND p.lang = ".$lang->lang);
            $project = $db->loadObject();
            $smarty->assign("project", $project);

            // Get subprojects
            $db->setQuery("SELECT * FROM subprojects WHERE parent_id = $id AND status = 1 AND lang = ".$lang->lang);
            $subprojects = $db->loadObjectList();

            $poze = array();

            // Get project pictures
            $pictures = SUPLPhotoHelper::get_pictures("projects", $id);
            if($pictures)
                $poze = array_merge($poze, $pictures);

            if(count($subprojects)>0) {
                foreach ($subprojects as $key=>$item) {
                    $pictures = SUPLPhotoHelper::get_pictures("subprojects", $item->id);
                    if($pictures)
                        $poze = array_merge($poze, $pictures);
                }
            }

            // Assign pictures
            $smarty->assign("poze", $poze);
        }


        $doc->setPageTitle("Pure Mess Design");
        $doc->setMetaTag("description", "Pure Mess Design");

        $smarty->display($this->tplName);
    }

    /**
     * About us page
     *
     * @access: public
     * @return: null
     */
    function despre() {
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $smarty = $app->getTemplate();
        $db=SDatabase::getInstance();
        $lang = SLanguage::getInstance();
        objInitVar($this, "site/despre.tpl", "despre", "despre", "", "", "");

        $doc->setPageTitle("Pure Mess Design");
        $doc->setMetaTag("description", "Pure Mess Design");

        $smarty->display($this->tplName);
    }

    /**
     * Services page
     *
     * @access: public
     * @return: null
     */
    function servicii() {
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $smarty = $app->getTemplate();
        $db=SDatabase::getInstance();
        $lang = SLanguage::getInstance();
        objInitVar($this, "site/servicii.tpl", "servicii", "servicii", "", "", "");

        $doc->setPageTitle("Pure Mess Design");
        $doc->setMetaTag("description", "Pure Mess Design");

        $smarty->display($this->tplName);
    }

    function contact()
    {
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $db = SDatabase::getInstance();
        $smarty = $app->getTemplate();
        objInitVar($this, "/site/contact.tpl", "contact", "contact", "", "", "");

        //set Spoof ID
        $_SESSION[SESS_IDX]['spoofContactId']=getNoSpoofID();
        $smarty->assign("spoofContactId", $_SESSION[SESS_IDX]['spoofContactId']);

        if($smarty->isCached($this->tplName, CACHE_ID)) {
            $smarty->display($this->tplName, CACHE_ID);
            return;
        }

        $breadcrumbs=array("Acasa"=>'/', 'Contact'=>"");
        $smarty->assign("breadcrumbs", $breadcrumbs);
        $doc->setPageTitle("Contact | Pure Mess Design");
        $doc->setMetaTag("description", "Contact | Pure Mess Design");

        $smarty->display($this->tplName, CACHE_ID);
    }

    function process_contact() {
        if( $_SESSION[SESS_IDX]['spoofContactId'] != $_POST['spoofContactId'] ) {
            systemMessage::addMessage("Am intampinat o eroare la procesarea mesajului. Te rugam incearca din nou!");
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $nume = getFromRequest($_POST,"nume","");
            $tel=getFromRequest($_POST,'telefon',"");
            $email=getFromRequest($_POST,'email',"");
            $mesaj=getFromRequest($_POST,'mesaj',"");

            include_once(LIB_DIR."utile/validator.php");

            $v = new Validator($_POST);
            $v->setErrorMessage('nume', 'Introduceti numele');
            $v->setErrorMessage('email', 'Introduceti adresa de email!');
            $v->setErrorMessage('telefon', 'Introduceti numarul de telefon!');
            $v->setErrorMessage('mesaj', 'Introduceti mesajul!');

            $v->filledIn('nume');
            $v->filledIn('email');
            $v->filledIn('telefon');
            $v->filledIn('mesaj');
            $v->setErrorMessage('email', 'Adresa de email este invalida!');
            $v->email('email');

            if (!$v->isValid())
            {
                $jsErrors="";
                foreach ($v->getErrors() as $k => $error)
                {
                    $jsErrors .= $error."<br/>";
                }
                systemMessage::addMessage($jsErrors,2);
                redirect($_SERVER['HTTP_REFERER']);
            }
            else
            {
                require_once(LIB_DIR."db/db_table.php");
                $db=SDatabase::getInstance();
                $sugestii = STable::tableInit("contact", "id");
                $sugestii->bind($_POST);
                $sugestii->ip = getVisitorIP();
                $sugestii->date = date("Y-m-d H:i:s", time());
                $sugestii->tip = 'contact';
                $sugestii->store();
                unset($_POST);

                $subject = "Un nou mesaj in contact pe site-ul Pure Mess";
                $message="Nume: ".$sugestii->nume."<br />";
                $message.="Email: ".$sugestii->email."<br />";
                $message.="Telefon: ".$sugestii->telefon."<br />";
                $message.="Mesaj: <br />".$sugestii->mesaj."<br />";
                send_mail(EMAIL_CONTACT_ADDR, EMAIL_FROM_ADDR ,$sugestii->nume, $message, $subject);

                systemMessage::addMessage("Mesajul a fost trimis!");
                redirect("/contact/succes/");
            }
        }
    }


    function error_404() {
        $app = SApp::getInstance();
        $doc = SDocument::getInstance();
        $db = SDatabase::getInstance();
        $smarty = $app->getTemplate();
        objInitVar($this, "site/404.tpl", "404", "404", "", "", "");

        $error = $db->getEscaped($_SERVER['REQUEST_URI']);

        // get redirect to if exists in db
        $db->setQuery("SELECT * FROM redirect WHERE redirect_from = ".$db->quote($error));
        $result = $db->loadAssoc();
        if(isset($result['redirect_to']) && $result['redirect_to']!="") {
            $redirect_to = $result['redirect_to'];
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirect_to);
        } else {
            if(isset($result['redirect_from']) && $result['redirect_from']!="") {
            } else {
                $db->setQuery("INSERT INTO redirect SET  redirect_from = '$error', redirect_to = '/', redirect_status = 0, redirect_date = NOW()");
                $db->query();
            }
        }

        if($smarty->isCached($this->tplName, CACHE_ID)) {
            $smarty->display($this->tplName, CACHE_ID);
            return;
        }

        $doc->setPageTitle("Eroare 404 | Swiss Gold");
        $breadcrumbs=array("Acasa"=>'/', 'Rezultate'=>"");
        $smarty->assign("breadcrumbs", $breadcrumbs);

        $smarty->display($this->tplName, CACHE_ID);
        $_SESSION[SESS_IDX]["legal_display"] = 1;
    }

    /* Site Map Page
	 *
	 * @access: public
	 * @return: null
	*/
    function sitemap()
    {
        $db=SDatabase::getInstance();

        header('Content-type: application/xml; charset="utf-8"',true);

        echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        echo
            "<url>
				<loc>".ROOT_HOST."</loc>
				<changefreq>weekly</changefreq>
				 <priority>1.0</priority>
			</url>

			<url>
                <loc>".ROOT_HOST."despre-noi/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."amanet/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."vindem/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."intrebari-frecvente/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."blog/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."contact/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.90</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/ceasuri/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.80</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/aur-si-argint/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.80</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/diamante/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.80</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/bijuterii-cu-diamante/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.80</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/genti-de-lux/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.80</priority>
            </url>

            <url>
                <loc>".ROOT_HOST."cumparam/antichitati/</loc>
                <changefreq>weekly</changefreq>
                <priority>0.80</priority>
            </url>";

        $db->setQuery("SELECT * FROM produse_category WHERE status = 1");
        $categorii_produse = $db->loadAssocList();
        foreach($categorii_produse as $key=>$value) {
            echo "<url>
                    <loc>".ROOT_HOST."vindem/".$value['seo_name']."/</loc>
                    <changefreq>weekly</changefreq>
                    <priority>0.70</priority>
                </url>";
        }

        $db->setQuery("SELECT * FROM produse WHERE status = 1");
        $produse = $db->loadAssocList();
        foreach($produse as $key=>$value) {
            $poze = get_pictures("produse", $value['id']);
            echo "<url>
                    <loc>".ROOT_HOST."vindem/".seo_link($value['nume'], $value['id']).".html</loc>";

            if($poze) {
                foreach ($poze as $k => $v) {
                    echo "<image:image>
                              <image:loc>" . ROOT_HOST . "brand-picture/" . $v['file'] . "</image:loc>
                        </image:image>";
                }
            }
            echo "<changefreq>weekly</changefreq>
                    <priority>0.60</priority>
                </url>";
        }

        $db->setQuery("SELECT * FROM article_category WHERE status = 1");
        $blog = $db->loadAssocList();
        foreach($blog as $key=>$value) {
            echo "<url>
                    <loc>".ROOT_HOST."blog/".$value['seo_name']."/</loc>
                    <changefreq>weekly</changefreq>
                    <priority>0.80</priority>
                </url>";
        }

        $db->setQuery("SELECT * FROM article WHERE status = 1");
        $articole = $db->loadAssocList();
        foreach($articole as $key=>$value) {
            echo "<url>
                    <loc>".ROOT_HOST."blog/".seo_link($value['title'], $value['id']).".html</loc>";

            if($value['photos'] && $value['photos']!="") {
                echo "<image:image>
                        <image:loc>" . ROOT_HOST . "poze-blog/list/" . $value['photos'] . "</image:loc>
                    </image:image>";
            }
            echo "<changefreq>weekly</changefreq>
                    <priority>0.7</priority>
                </url>";
        }

        $db->setQuery("SELECT * FROM locatii l LEFT JOIN orase o ON l.locatii_oras = o.orase_id  WHERE l.locatii_status = 1");
        $locatii=$db->loadAssocList();
        foreach($locatii as $key=>$item) {
            echo "<url>
                    <loc>".ROOT_HOST."contact/".seo_link($item['orase_nume'])."/".seo_link($item['locatii_nume'], $item['locatii_id']).".html</loc>
                    <changefreq>weekly</changefreq>
                    <priority>0.80</priority>
                </url>";
        }

        echo "</urlset>";

        exit;
    }
}
?>