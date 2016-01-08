<?php

	/**
	* Plugin Name: MobileSharing
	* Description: Add share menu to your blog web app
	* Author: WiziApp Solutions Ltd.
	* Version: 1.1.0
	* Author URI: http://www.wiziapp.com/
	*/

	add_action('init', 'wiziappshare_init');


	function wiziappshare_init()
	{
                add_action('admin_menu', 'wiziappshare_admin_menu');
                add_action( 'wp_enqueue_scripts', 'wiziappshare_add_my_stylesheet' );
                add_action('wp_footer', 'wiziappshare_footer');
                add_filter('the_content', 'wiziappshare_content');
                add_action( 'admin_init', 'wiziappshare_admin_init' );
	}

        function wiziappshare_admin_init()
        {
            wp_register_style( 'wiziappshare_admin_css', plugins_url('wiziappshareadmin.css', __FILE__) );
            wp_enqueue_style( 'wiziappshare_admin_css' );
        }

        function wiziappshare_add_my_stylesheet() {
            // Respects SSL, Style.css is relative to the current file
            wp_register_style( 'wiziappshare_css', plugins_url('wiziappshare.css', __FILE__) );
            wp_enqueue_style( 'wiziappshare_css' );
            //wp_register_script( 'wiziappshare_js', plugins_url('wiziappshare.js', __FILE__) );
            wp_enqueue_script( 'wiziappshare_js' , plugins_url('wiziappshare.js', __FILE__) , array( 'jquery' ) );
        }

	function wiziappshare_admin_menu()
	{
		add_menu_page('MobileSharing', 'MobileSharing', 'administrator', 'wiziappshare', 'wiziappshare_admin_menu_page');
	}

	function wiziappshare_admin_menu_page()
	{

            if (isset($_POST['wiziappshare_hidden']))
            {
                $floatIconDisplay = $_POST['wiziappshare_float_display'] == "true" ? true : false;
                update_option('wiziappshare_float_display', $floatIconDisplay);
                $postScreen = $_POST['wiziappshare_post'];
                update_option('wiziappshare_post', $postScreen);
                $pageScreen = $_POST['wiziappshare_page'];
                update_option('wiziappshare_page', $pageScreen);
                $mailInd = $_POST['wiziappshare_mail'];
                update_option('wiziappshare_mail', $mailInd);
                $googleInd = $_POST['wiziappshare_google'];
                update_option('wiziappshare_google', $googleInd);
                $facebookInd = $_POST['wiziappshare_facebook'];
                update_option('wiziappshare_facebook', $facebookInd);
                $twitterInd = $_POST['wiziappshare_twitter'];
                update_option('wiziappshare_twitter', $twitterInd);
                $linkedinInd = $_POST['wiziappshare_linkedin'];
                update_option('wiziappshare_linkedin', $linkedinInd);
            }
            else
            {
               $floatIconDisplay = get_option('wiziappshare_float_display',false);
               $postScreen = get_option('wiziappshare_post',true);
               $pageScreen = get_option('wiziappshare_page');
               $mailInd = get_option('wiziappshare_mail',true);
               $googleInd = get_option('wiziappshare_google',true);
               $facebookInd = get_option('wiziappshare_facebook',true);
               $twitterInd = get_option('wiziappshare_twitter',true);
               $linkedinInd = get_option('wiziappshare_linkedin',true);
            }

        ?>

        <div class="wiziappshare_main">
            <form action="" method="post">
                <input type="hidden" name="wiziappshare_hidden" value="Y">
                <div class="wiziappshare_general">
                    <div class="wiziappshare_title">General Settings</div>
                    <div class="wiziappshare_inner_title">Mobile Sharing Style:</div>
                    <div class="wiziappshare_line"><input type="radio" name="wiziappshare_float_display" onchange='document.forms[0].submit();' value="false" <?php if ($floatIconDisplay == false) echo 'checked';?> >Sharing buttons are displayed on the posts/pages bottom</div>
                    <div class="wiziappshare_line"><input type="radio" name="wiziappshare_float_display" onchange='document.forms[0].submit();' value="true" <?php if ($floatIconDisplay == true) echo 'checked';?> >Floating icon, which opens the sharing buttons, is displayed on top of the posts/pages</div>
                    <div class="wiziappshare_inner_title">Mobile Display Screens:</div>
                    <div class="wiziappshare_line"><input type="checkbox" name="wiziappshare_post" onchange='document.forms[0].submit();' value="true" <?php if ($postScreen == true) echo 'checked';?> >Post's screen</div>
                    <div class="wiziappshare_line"><input type="checkbox" name="wiziappshare_page" onchange='document.forms[0].submit();' value="true" <?php if ($pageScreen == true) echo 'checked';?>>Page's screen</div>
                </div>
                <div class="wizappshare_options">
                    <div class="wiziappshare_title">Sharing options</div>
                    <div class="wiziappshare_inner_title">Enable sharing via:</div>
                    <div class="wiziappshare_line wiziappshare_mail_line"><input type="checkbox" name="wiziappshare_mail"  value="true" <?php if ($mailInd == true) echo 'checked';?>>eMail</div>
                    <div class="wiziappshare_line wiziappshare_google_line"><input type="checkbox" name="wiziappshare_google"  value="true" <?php if ($googleInd == true) echo 'checked';?>>GooglePlus</div>
                    <div class="wiziappshare_line wiziappshare_facebook_line"><input type="checkbox" name="wiziappshare_facebook"  value="true" <?php if ($facebookInd == true) echo 'checked';?>>Facebook</div>
                    <div class="wiziappshare_line wiziappshare_linkedin_line"><input type="checkbox" name="wiziappshare_linkedin"  value="true" <?php if ($linkedinInd == true) echo 'checked';?>>Linkedin</div>
                    <div class="wiziappshare_line wiziappshare_twitter_line"><input type="checkbox" name="wiziappshare_twitter"  value="true" <?php if ($twitterInd == true) echo 'checked';?>>Twitter</div>

                </div>
                <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
            </form>
        </div>

        <?php
            if (isset($_POST['wiziappshare_hidden']))
            {
                ?>
                <p> Changes saved </p>
                <?php
            }
	}

	function wiziappshare_footer() {

            if (get_option('wiziappshare_float_display',false)) {
                if(strstr($_SERVER['HTTP_USER_AGENT'],'72dcc186a8d3d7b3d8554a14256389a4') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'Android'))
                {
                    $postScreen = get_option('wiziappshare_post',true);
                    $pageScreen = get_option('wiziappshare_page');

                        $title = urlencode(get_the_title());
                        $url = urlencode(get_permalink());
                        $mailInd = get_option('wiziappshare_mail',true);
                        $googleInd = get_option('wiziappshare_google',true);
                        $facebookInd = get_option('wiziappshare_facebook',true);
                        $twitterInd = get_option('wiziappshare_twitter',true);
                        $linkedinInd = get_option('wiziappshare_linkedin',true);
                        $initialBottom = 50;

                        echo '<div id="wiziappshare-lightbox-shadow"></div>';

                        if (($pageScreen == true && is_page()) || ($postScreen == true && is_single()) )
                        {
                            echo '<div class="wiziappshare-toolbar wiziappshare-button"></div>';
                        }
                        else
                        {
                            echo '<div class="wiziappshare-toolbar wiziappshare-button" style="display : none"></div>';

                        }
                        echo '<div class=wiziappshare-menu>';
                        if ($mailInd == true)
                        {
                            $initialBottom = $initialBottom +50;
                            echo '<a id=wiziappshare-mail class=wiziappshare-button target="_blank" href="mailto:?subject=' . $title . '&amp;body=' . $url . '" style="bottom:' . $initialBottom . 'px;"></a>';
                        }
                        if ($googleInd == true)
                        {
                            $initialBottom = $initialBottom +50;
                            echo '<a id=wiziappshare-google class=wiziappshare-button target="_blank" href="https://plus.google.com/share?url=' . $url . '" style="bottom:' . $initialBottom . 'px;"></a>';
                        }
                        if ($linkedinInd == true)
                        {
                            $initialBottom = $initialBottom +50;
                            echo '<a id=wiziappshare-linkedin class=wiziappshare-button target="_blank" href="http://www.linkedin.com/shareArticle?url=' . $url . '" style="bottom:' . $initialBottom . 'px;"></a>';
                        }
                        if ($facebookInd == true)
                        {
                            $initialBottom = $initialBottom +50;
                            echo '<a id=wiziappshare-facebook class=wiziappshare-button target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" style="bottom:' . $initialBottom . 'px;"></a>';
                        }
                        if ($twitterInd == true)
                        {
                            $initialBottom = $initialBottom +50;
                            echo '<a id=wiziappshare-twitter class=wiziappshare-button target="_blank" href="https://twitter.com/intent/tweet?source=webclient&text=' . $url . '" style="bottom:' . $initialBottom . 'px;"></a>';
                        }
                        echo '</div>';
                }
            }
	}
        function wiziappshare_content($content) {
            static $firstTime = 0;
            if ($firstTime == 0) {
		$firstTime = 1;
                $url = get_permalink();
                $postScreen = get_option('wiziappshare_post',true);
                $pageScreen = get_option('wiziappshare_page');

                if (($pageScreen == true && is_page()) || ($postScreen == true && is_single()) )
                {
                    if (get_option('wiziappshare_float_display',false)) {


                            echo '<div class=wiziappshare_show>' . esc_html($url) . '</div>';
                    }
                    else
                    {
                        if(strstr($_SERVER['HTTP_USER_AGENT'],'72dcc186a8d3d7b3d8554a14256389a4') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'Android'))
                        {
                            $postScreen = get_option('wiziappshare_post',true);
                            $pageScreen = get_option('wiziappshare_page');

                                $title = urlencode(get_the_title());
                                $url = urlencode($url);
                                $mailInd = get_option('wiziappshare_mail',true);
                                $googleInd = get_option('wiziappshare_google',true);
                                $facebookInd = get_option('wiziappshare_facebook',true);
                                $twitterInd = get_option('wiziappshare_twitter',true);
                                $linkedinInd = get_option('wiziappshare_linkedin',true);

                                echo '<ul class=wiziappshare-static-menu>';
                                if ($mailInd == true)
                                {
                                    echo '<li><a id=wiziappshare-mail class=wiziappshare-button target="_blank" href="mailto:?subject=' . $title . '&amp;body=' . $url . '"></a></li>';
                                }
                                if ($googleInd == true)
                                {
                                    echo '<li><a id=wiziappshare-google class=wiziappshare-button target="_blank" href="https://plus.google.com/share?url=' . $url . '"></a></li>';
                                }
                                if ($linkedinInd == true)
                                {
                                    echo '<li><a id=wiziappshare-linkedin class=wiziappshare-button target="_blank" href="http://www.linkedin.com/shareArticle?url=' . $url . '"></a></li>';
                                }
                                if ($facebookInd == true)
                                {
                                    echo '<li><a id=wiziappshare-facebook class=wiziappshare-button target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '"></a></li>';
                                }
                                if ($twitterInd == true)
                                {
                                    echo '<li><a id=wiziappshare-twitter class=wiziappshare-button target="_blank" href="https://twitter.com/intent/tweet?source=webclient&text=' . $url . '"></a></li>';
                                }
                                echo '</ul>';
                        }
                    }
                }
            }
            return $content;
        }