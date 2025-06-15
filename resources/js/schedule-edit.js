// Drag & drop logic for schedule edit page

// import { Livewire } from 'livewire'; // НЕ НУЖНО!

export function initScheduleEdit() {
    let draggedEl = null;
    let sourcePanel = false;
    let dropSuccess = false;
    let dragFromCellKey = null;

    // Разрешаем дроп везде, чтобы событие drop гарантированно срабатывало
    document.addEventListener('dragover', e => {
        if (e.target.closest('.droppable-cell')) {
            e.preventDefault();
        }
    });

    // dragstart – запоминаем элемент и источник
    document.addEventListener('dragstart', e => {
        const el = e.target.closest('[draggable="true"]');
        if (!el) return;
        draggedEl = el;
        sourcePanel = !!el.closest('#masters-panel');
        dropSuccess = false;
        const parentCell = el.closest('.droppable-cell');
        dragFromCellKey = parentCell ? parentCell.dataset.cellKey : null;
        e.dataTransfer.setData('text/plain', el.dataset.userId);
        e.dataTransfer.effectAllowed = sourcePanel ? 'copy' : 'move';
        if (!sourcePanel) {
            setTimeout(() => el.classList.add('hidden'), 0);
        }
    });

    // dragend – если дроп был вне таблицы → удалить элемент
    document.addEventListener('dragend', () => {
        if (!dropSuccess && draggedEl && !sourcePanel) {
            // Удаляем из DOM
            draggedEl.parentElement && draggedEl.parentElement.removeChild(draggedEl);
            const payload = { userId: draggedEl.dataset.userId };
            if(dragFromCellKey){ payload.cell = dragFromCellKey; }
            window.Livewire.dispatch('cardRemoved', payload);
        }
        if (draggedEl) {
            draggedEl.classList.remove('hidden');
        }
        draggedEl = null;
        dragFromCellKey = null;
    });

    // drop на ячейки таблицы
    document.addEventListener('drop', e => {
        const cell = e.target.closest('.droppable-cell');
        if (!cell) return; // дроп не в таблицу → обработается в dragend

        e.preventDefault();
        dropSuccess = true;

        const userId = e.dataTransfer.getData('text/plain');
        if (!userId) return;

        const existingCard = cell.querySelector('.shifts-card');
        if (existingCard && existingCard.dataset.userId == userId) return;

        let newCardEl;
        if (sourcePanel) {
            const template = document.querySelector(`#master-card-template-${userId}`);
            if (!template) return;
            newCardEl = template.content.firstElementChild.cloneNode(true);
        } else {
            newCardEl = draggedEl;
        }

        // Удаляем существующую карточку в ячейке (если есть)
        if (existingCard) {
            const removedId = existingCard.dataset.userId;
            existingCard.parentElement.removeChild(existingCard);
            window.Livewire.dispatch('cardRemoved', { userId: removedId, cell: cell.dataset.cellKey });
        }

        // Удаляем карточку из её предыдущей ячейки (если она была внутри таблицы)
        let fromCellKey = null;
        if(!sourcePanel && draggedEl && draggedEl.parentElement){
            const parentCell = draggedEl.parentElement.closest('.droppable-cell');
            if(parentCell) fromCellKey = parentCell.dataset.cellKey;
        }

        cell.appendChild(newCardEl);
        if(fromCellKey){
            window.Livewire.dispatch('cardMoved', { userId: userId, from: fromCellKey, to: cell.dataset.cellKey });
        } else {
            window.Livewire.dispatch('cardAdded', { userId: userId, cell: cell.dataset.cellKey });
        }
    });

    // Кнопка «Сохранить»
    const saveBtn = document.getElementById('save-shifts-btn');
    if(saveBtn){
        saveBtn.addEventListener('click', () => {
            window.Livewire.dispatch('saveShifts');
        });
    }
}

// Автоинициализация при load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScheduleEdit);
} else {
    initScheduleEdit();
} 