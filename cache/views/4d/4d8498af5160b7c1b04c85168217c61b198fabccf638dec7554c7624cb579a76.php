<?php

/* home.twig */
class __TwigTemplate_10466fa2edb32f45e53d71c869bb5e281b6d174737876c2804aad8aa790c4ec2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "Home template

";
        // line 3
        echo twig_escape_filter($this->env, twig_var_dump($this->env, $context, ($context["user"] ?? null)), "html", null, true);
    }

    public function getTemplateName()
    {
        return "home.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  23 => 3,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("Home template

{{ dump(user) }}", "home.twig", "/Users/littlebigprogramming/Sites/no-framework/views/home.twig");
    }
}
