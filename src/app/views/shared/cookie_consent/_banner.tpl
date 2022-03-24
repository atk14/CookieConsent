{assign consent CookieConsent::GetInstance()}
{assign settings CookieConsent::GetSettings()}

{if $settings->needsToBeConfirmed() && ($namespace!="" || $controller!="cookie_consents" || $action!="edit") && (!$page || !is_a($page,"TableRecord") || !$page->hasKey("code") || $page->getCode()!=="privacy_policy")}
	<div class="cookie_consent_banner_container" id="js--cookie_consent_banner_container">
		<div>
		<!--googleoff: all-->
			<div class="container-fluid">
					<h3 class="cookie_consent_banner__title">
						{if defined("USING_FONTAWESOME") && constant("USING_FONTAWESOME")}<span class="fas fa-cookie-bite"></span>{/if}
						{$consent->getBannerTitle()}
					</h3>

					<div class="cookie_consent_banner__text">
					{!$consent->getBannerText()|markdown}
					</div>

					<p  class="cookie_consent_banner__buttons">
						{a_remote namespace="" controller="cookie_consents" action="edit" _class="btn btn-outline-secondary cookie_consent_banner__btn-edit" _rel="nofollow"}{t}Nastavení{/t}{/a_remote}
						{a_remote namespace="" controller="cookie_consents" action="accept_all" _class="btn btn-primary cookie_consent_banner__btn-accept-all" _method=post _rel="nofollow"}{t}OK{/t}{/a_remote}
					</p>

					<p class="cookie_consent_banner__rejection-link rejection-link">
						{capture assign=a}<a href="{link_to namespace="" controller="cookie_consents" action="reject_all"}" class="remote_link post" data-remote="true" data-method="post" rel="nofollow">{/capture}
						{t 1=$a escape=false}Souhlas můžete odmítnout %1 zde{/t}</a>
					</p>
			</div>
		<!--googleon: all-->
		</div>
	</div>
{/if}
