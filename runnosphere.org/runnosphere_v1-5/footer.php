        <div id="footer">
        
        	Copyright &copy; <?php if(date('Y') == "2010"){ print('2010'); }else{ print('2010 - '.date('Y')); } ?> <a href="<?php bloginfo('url'); ?>" title="Runnosphere.org">Runnosphere.org</a> | Powered by <a href="http://wordpress.org" title="Wordpress" target="_blank">Wordpress</a> | Développé en courant par <a href="http://twitter.com/jeremieP" title="@JeremieP on Twitter">@jeremieP</a> | <a href="javascript:go_scrolling_top();" title="Retour en haut">Retour en haut</a>
        
        </div>
    
    </div>
    
    <script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-19966191-1']);
		_gaq.push(['_setDomainName', '.runnosphere.org']);
		_gaq.push(['_trackPageview']);
		(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
    
    <?php wp_footer(); ?>
    
</body>

</html>