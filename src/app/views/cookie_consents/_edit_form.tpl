{if $request->xhr()}

	{form_remote _novalidate="novalidate" _role="form"}
		{render partial="edit_form_content"}
	{/form_remote}

{else}

	{form _novalidate="novalidate" _role="form"}
		{render partial="edit_form_content"}
	{/form}

{/if}


