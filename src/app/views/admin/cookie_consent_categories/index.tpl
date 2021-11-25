<h3 id="cookie_consent_categories">{button_create_new return_to_anchor="cookie_consent_categories"}{t}Přidat novou kategorii{/t}{/button_create_new} {$page_title}</h3>

<ul class="list-group list-group-flush list-sortable" data-sortable-url="{link_to action="set_rank"}">
	{render partial="cookie_consent_category_item" from=$cookie_consent_categories}
</ul>
