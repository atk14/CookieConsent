<li class="list-group-item" data-id="{$cookie_consent_category->getId()}">

	{if "USING_BOOTSTRAP3"|dump_constant}

		{* Bootstrap 3 *}
		
		<div class="pull-left">
			<big>{$cookie_consent_category->getTitle()}</big>
		</div>

		<div class="pull-left" style="padding-left: 2em;">
			{$cookie_consent_category->getCode()}
		</div>

		{if $cookie_consent_category->isNecessary()}
			<div class="pull-left" style="padding-left: 2em;">
				<em>{t}necessary{/t}</em>
			</div>
		{/if}
		
		{if !$cookie_consent_category->isActive()}
			<div class="pull-left" style="padding-left: 2em;">
				<em>{t}inactive{/t}</em>
			</div>
		{/if}

		{dropdown_menu class="pull-right"}
			{a action=edit id=$cookie_consent_category}{!"pencil-alt"|icon} {t}Edit{/t}{/a}

			{if $cookie_consent_category->isDeletable()}
				{capture assign="confirm"}{t 1=$cookie_consent_category->getTitle()|h escape=no}You are about to permanently delete cookie consent category %1.
Are you sure about that?{/t}{/capture}
				{a_destroy id=$cookie_consent_category _confirm=$confirm}{!"trash-alt"|icon} {t}Delete{/t}{/a_destroy}
			{/if}
		{/dropdown_menu}

	{else}

		{* Bootstrap 4 *}

		<div class="item__properties">
			<div class="item__title">
				{$cookie_consent_category->getTitle()}
			</div>

			<div class="item__code">
				{$cookie_consent_category->getCode()}
			</div>

			<div class="item__properties">
				{if $cookie_consent_category->isNecessary()}
					<em>{t}necessary{/t}</em>
				{/if}
			</div>

			<div class="item__properties">
				{if !$cookie_consent_category->isActive()}
					<em>{t}inactive{/t}</em>
				{/if}
			</div>

			<div class="item__controls">
				{dropdown_menu}
					{a action=edit id=$cookie_consent_category}{!"pencil-alt"|icon} {t}Edit{/t}{/a}

					{if $cookie_consent_category->isDeletable()}
						{capture assign="confirm"}{t 1=$cookie_consent_category->getTitle()|h escape=no}You are about to permanently delete cookie consent category %1.
Are you sure about that?{/t}{/capture}
						{a_destroy id=$cookie_consent_category _confirm=$confirm}{!"trash-alt"|icon} {t}Delete{/t}{/a_destroy}
					{/if}
				{/dropdown_menu}
			</div>
		</div>

	{/if}
</li>
