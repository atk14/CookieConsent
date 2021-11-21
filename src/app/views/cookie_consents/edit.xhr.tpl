{if $request->post() && !$form->has_errors()}

	$("#cookie_consent_modal_id").modal("hide");
	$("#js--cookie_consent_banner_container").fadeOut("slow");

{elseif $request->post()}

	$form.replaceWith({jstring}{render partial="edit_form"}{/jstring});
	{* TODO: move to the top edge of the modal dialog *}

{else}

	$("#cookie_consent_modal_id").remove();

	var $modal = $({jstring}{modal id=cookie_consent_modal_id title="{t}Nastavení cookies{/t}"}
		{render partial="edit_form"}
	{/modal}{/jstring});

	$modal.appendTo("body");

	$("#cookie_consent_modal_id").modal("show");

{/if}
