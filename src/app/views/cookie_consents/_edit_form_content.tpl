{render partial="shared/form_error"}
<fieldset>
	{render partial="shared/form_field" fields=$form->get_field_keys()}
</fieldset>
<fieldset>
	<div class="form-group">
		<span class="button-container">
			<button type="submit" class="btn btn-outline-secondary">{t}Souhlasím s použitím vybraných cookies{/t}</button>
			{if $request->xhr()}
				{a_remote action="accept_all" dialog="1" _method=post _class="btn btn-primary"}{t}Souhlasím s použitím všech cookies{/t}{/a_remote}
			{else}
				{a action="accept_all" dialog="1" _method=post _class="btn btn-primary"}{t}Souhlasím s použitím všech cookies{/t}{/a}
			{/if}
		</span>
	</div>
</fieldset>
