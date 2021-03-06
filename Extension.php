<?php
// Facebook Like Extension for Bolt

namespace Bolt\Extension\Bolt\FacebookLike;

use Bolt\Extensions\Snippets\Location as SnippetLocation;

class Extension extends \Bolt\BaseExtension
{
    public function getName()
    {
        return "Facebook Like";
    }

    public function initialize()
    {

        if (empty($this->config['style'])) {
            $this->config['style'] = "standard";
        }
        if (empty($this->config['width'])) {
            $this->config['width'] = "350px";
        }
        if (empty($this->config['verb'])) {
            $this->config['verb'] = "like";
        }
        if (empty($this->config['scheme'])) {
            $this->config['scheme'] = "light";
        }
        if (empty($this->config['url'])) {
            $this->config['url'] = $this->app['paths']['canonicalurl'];
        }
        if (empty($this->config['language'])) {
            $this->config['language'] = "en_US";
        }

        $this->addSnippet(SnippetLocation::END_OF_BODY, 'facebookScript');
        $this->addTwigFunction('facebooklike', 'facebookLike');

    }

    public function facebookScript()
    {

        $language = $this->config['language'];

        $html = <<< EOM
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/$language/all.js#xfbml=1";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
EOM;

        return $html;

    }

    public function facebookLike()
    {

        // code from http://developers.facebook.com/docs/reference/plugins/like/

        $html = <<< EOM
    <div class="fb-like" data-href="%url%" data-send="false" data-layout="%style%" data-width="%width%"
    data-show-faces="false" data-action="%verb%" data-colorscheme="%scheme%"></div>
EOM;
        // data-href="http://example.org"

        $html = str_replace("%url%", $this->config['url'], $html);
        $html = str_replace("%style%", $this->config['style'], $html);
        $html = str_replace("%width%", $this->config['width'], $html);
        $html = str_replace("%verb%", $this->config['verb'], $html);
        $html = str_replace("%scheme%", $this->config['scheme'], $html);

        return new \Twig_Markup($html, 'UTF-8');

    }
}
