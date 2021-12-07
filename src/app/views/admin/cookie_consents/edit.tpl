{dropdown_menu align="right" clearfix=false}
	{a action="cookie_consent_statistics/index"}{t}Statistika klikání{/t}{/a}
{/dropdown_menu}

<h1>{$page_title}</h1>

{render partial="shared/form"}

{render_component controller="cookie_consent_categories" action="index"}
