document.addEventListener('DOMContentLoaded', () => {
    if (!window.popupQueue || !Array.isArray(window.popupQueue)) {
        console.error('popupQueue is not defined or is not an array.');
        return;
    }

    let shownPopups = JSON.parse(localStorage.getItem('shownPopups')) || [];

    const schedulePopup = (index) => {
        if (index >= window.popupQueue.length) {
            return;
        }

        const popupData = window.popupQueue[index];

        if (shownPopups.includes(popupData.id)) {
            schedulePopup(index + 1);
            return;
        }

        if (popupData.triggerAfterPrevious && index > 0 && !shownPopups.includes(window.popupQueue[index - 1].id)) {
            return;
        }

        const delayTime = popupData.displayTime * 1000;

        setTimeout(() => {
            showPopup(index);
        }, delayTime);
    };

    const showPopup = (index) => {
        if (index >= window.popupQueue.length) {
            return;
        }

        const popupData = window.popupQueue[index];

        if (shownPopups.includes(popupData.id)) {
            schedulePopup(index + 1);
            return;
        }

        const popupElement = document.getElementById(`popup${popupData.id}`);
        if (!popupElement) {
            schedulePopup(index + 1);
            return;
        }

        popupElement.style.display = 'block';
        shownPopups.push(popupData.id);
        localStorage.setItem('shownPopups', JSON.stringify(shownPopups));

        const closeButton = popupElement.querySelector('.shopmodal-close-button');
        closeButton.addEventListener('click', () => {
            popupElement.style.display = 'none';

            schedulePopup(index + 1);
        });
    };

    schedulePopup(0);
});
