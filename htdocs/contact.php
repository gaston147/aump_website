<!DOCTYPE html> 
<html lang="fr"> 
 
<head> 
	<meta charset="utf-8"> 
	<meta name="viewport" content="maximum-scale=1">

	<title>Bluecraft - Contact</title> 
	<meta name="description" content="Description du site" /> 
	<link rel="stylesheet" href="style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="js/jquery-site.js"></script>
    <!-- pour rendre compatible les navigateurs en dessous de ie9 -->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head> 
 
<body>
	<div id="conteneur">
    
    
    <header id="header-site">
    
    <div id="logo"><a href="index.html"><img src="images/logo.png" alt="logo"></a></div>
    

	<div id="nav-slider">

    <nav id="nav-principal">
    
		<ul>

    		<li><a  href="index.html">Accueil</a></li>
            <li><a href="#">Nous rejoindre</a></li>
            <li><a href="blog.html">Blog</a></li>
            <li><a href="#">Forums</a></li>
            <li><a class="active" href="contact.html">Contact</a></li>
    
    	</ul>
    
    
    </nav>
    
    </div>
    
    
    </header>
    
    <div id="main">
    
    <aside id="sidebar">
    
    <div class="sidebar-block">
    	<div class="title"><h1>Bloc de texte</h1></div>
    
    	<div class="content">
    		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tortor libero, mollis a vehicula id, tincidunt nec sem. Proin accumsan turpis sit amet nisi sollicitudin tincidunt. </p>
   		</div>
    
    </div>
    
    
       <div class="sidebar-block">
    	<div class="title"><h1>Facebook</h1></div>
    
    	<div class="content">
<p><div class="fb-like-box" data-href="http://www.facebook.com/Weenox" data-width="160" data-height="250" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div></p>
   		</div>
    
    </div>
    
        <div class="sidebar-block">
    	<div class="title"><h1>Publicité</h1></div>
    
    	<div class="content">
    		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tortor libero, mollis a vehicula id, tincidunt nec sem. Proin accumsan turpis sit amet nisi sollicitudin tincidunt.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tortor libero, mollis a vehicula id, tincidunt nec sem. Proin accumsan turpis sit amet nisi sollicitudin tincidunt. </p>
   		</div>
    
    </div>
    
    </aside>


    <div id="main-sidebar">
    
    
    	<h1>Contactez-nous</h1>
 
    	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tortor libero, mollis a vehicula id, tincidunt nec sem. Proin accumsan turpis sit amet nisi sollicitudin tincidunt. Quisque vitae sollicitudin dui, non aliquet nulla.</p>
        
        
        <form name="input" action="html_form_action.asp" method="get">
		Prénom: <br /><input type="text" name="user"><br />
        
        Adresse e-mail:<br /> <input type="text" name="mail"><br />
        
         Votre message:<br />
        <textarea>
		</textarea><br />
        
		<input class="button" type="submit" value="Envoyer">
		</form>

    
    
   	 </div>
    
    
        <div class="clear"></div>
   
    
    
    
    </div>
   
   
      <footer id="footer-bas">
   
   <div id="copyright">Copyright © 2014 <a href="#">Bluecraft</a> . Tous droits réservés.</div>
   
   <div id="realisation">Kit graphique par <a href="http://weenox.com/">Weenox.com</a></div>
   
   
   </footer> 
    
    
    </div>
    
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_CA/all.js#xfbml=1&appId=358731754180827";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    
</body>


</html>