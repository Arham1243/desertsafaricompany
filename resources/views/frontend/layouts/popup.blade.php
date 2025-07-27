<div class="global-popup-wrapper" global-popup-wrapper>
    <div class="global-popup">
        <div class="global-popup__header">
            <div class="title">{{ $popup->title ?? '' }}</div>
            <div class="close-icon popup-close-icon" close-icon>
                <i class="bx bx-x"></i>
            </div>
        </div>
        <div class="global-popup__content">
            {!! $popup->content ?? '' !!}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popupWrapper = document.querySelector('[global-popup-wrapper]');
        const closeIcon = popupWrapper.querySelector('[close-icon]');

        setTimeout(() => {
            popupWrapper.classList.add('open');
        }, 1000);

        closeIcon.addEventListener('click', function() {
            popupWrapper.classList.remove('open');
        });

        popupWrapper.addEventListener('click', function(e) {
            if (e.target === popupWrapper) {
                popupWrapper.classList.remove('open');
            }
        });
    });
</script>
