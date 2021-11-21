<h1>{$page_title}</h1>

<h3>{t}Aktuální nastavení{/t}</h3>

{dump var=$settings}

<h3>{t}Obsah cookie se souhlasem{/t}</h3>

{if $settings->getCookieData()}
	{dump var=$settings->getCookieData()}
{else}
	<p><em>Cookie is not set.</em></p>
{/if}
