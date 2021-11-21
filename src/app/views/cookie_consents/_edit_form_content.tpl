{render partial="shared/form_error"}
<fieldset>
	{render partial="shared/form_field" fields=$form->get_field_keys()}
</fieldset>
<fieldset>
	<div class="form-group">
		<span class="button-container">
			<button type="submit" class="btn btn-secondary">{t}Souhlasím s použitím vybraných souborů cookies{/t}</button>
			{if $request->xhr()}
				{a_remote action="accept_all" _method=post _class="btn btn-primary"}{t}Souhlasím s použitím všech souborů cookies{/t}{/a_remote}
			{else}
				{a action="accept_all" _method=post _class="btn btn-primary"}{t}Souhlasím s použitím všech souborů cookies{/t}{/a}
			{/if}
		</span>
	</div>
</fieldset>
