{if $request->post() && !$form->has_errors()}

	{render partial="consent_update_event.xhr"}

	$("#cookie_consent_modal_id").modal("hide");
	$("#js--cookie_consent_banner_container").fadeOut("slow");

{elseif $request->post()}

	$form.replaceWith({jstring}{render partial="edit_form"}{/jstring});
	{* TODO: move to the top edge of the modal dialog *}

{else}

	$("#cookie_consent_modal_id").remove();

	var $modal = $({jstring}{modal id=cookie_consent_modal_id title="{t}Nastavení cookies{/t}"}
		{render partial="edit"}
	{/modal}{/jstring});

	$modal.appendTo("body");

	$("#cookie_consent_modal_id").modal("show");

{/if}
