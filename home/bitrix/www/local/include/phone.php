<?
    if ($arParams["CUSTOM_CLASS"])
    {
        $customClass = $arParams["CUSTOM_CLASS"];
    }
    $dop = $arParams["CUSTOM_SVG"];
?>

<a 
    title="Позвонить нам" 
    class="phone-number-wrapper <?=$customClass?>"
    onclick="ga('send','event','phone','clicked');yaCounter44820226.reachGoal('phone');" 
    href="tel:88005050476">

	<span class="number"> 8 <span>800&nbsp;505</span>&nbsp;04 76 </span>

	<svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
		<g clip-path="url(#clip0_279_7924_<?=$dop?>)">
			<path d="M21.5899 30C16.3307 30 10.7146 28.0675 6.32344 23.6765C1.93963 19.2926 0 13.6802 0 8.41014C0 3.76547 3.75773 0 8.41014 0C8.76955 0 9.0927 0.218789 9.22617 0.55248L12.9918 9.96656C13.1721 10.4173 12.9529 10.9287 12.5023 11.109L8.36572 12.7636C8.65834 17.5225 12.4783 21.3422 17.2364 21.6344L18.891 17.4979C19.0709 17.0479 19.5822 16.8279 20.0335 17.0082L29.4475 20.7738C29.7812 20.9073 30 21.2304 30 21.5899C30 26.2345 26.2423 30 21.5899 30ZM7.8252 1.78301C4.4591 2.07428 1.75781 4.89697 1.75781 8.41014C1.75781 13.7075 3.82066 18.6878 7.56645 22.4335C11.3122 26.1793 16.2925 28.2422 21.5899 28.2422C25.1019 28.2422 27.9255 25.5424 28.2171 22.1748L20.1967 18.9667L18.6403 22.8577C18.5068 23.1913 18.1836 23.4101 17.8242 23.4101C11.6292 23.4101 6.58986 18.3708 6.58986 12.1767C6.58986 11.8174 6.80865 11.4932 7.14234 11.3597L11.0333 9.80332L7.8252 1.78301Z" />
		</g>
		<defs>
			<clipPath id="clip0_279_7924_<?=$dop?>">
				<rect width="30" height="30" fill="white" />
			</clipPath>
		</defs>
	</svg>
</a>