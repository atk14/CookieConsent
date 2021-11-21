<h1>{button_create_new}{t}Přidat novou kategorii{/t}{/button_create_new} {$page_title}</h1>

<ul class="list-group list-group-flush list-sortable" data-sortable-url="{link_to action="set_rank"}">
	{render partial="cookie_consent_category_item" from=$cookie_consent_categories}
</ul>
