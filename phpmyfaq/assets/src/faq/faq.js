import { addElement } from '../utils';
import { createBookmark, createFaq, handleBookmarks, deleteBookmark } from '../api';
import { pushErrorNotification, pushNotification } from '../../../admin/assets/src/utils';

export const handleAddFaq = () => {
  const addFaqSubmit = document.getElementById('pmf-submit-faq');

  if (addFaqSubmit) {
    addFaqSubmit.addEventListener('click', async (event) => {
      event.preventDefault();
      event.stopPropagation();

      const formValidation = document.querySelector('.needs-validation');
      if (!formValidation.checkValidity()) {
        formValidation.classList.add('was-validated');
      } else {
        const form = document.querySelector('#pmf-add-faq-form');
        const loader = document.getElementById('loader');
        const formData = new FormData(form);
        const response = await createFaq(formData);

        if (response.success) {
          loader.classList.add('d-none');
          const message = document.getElementById('pmf-add-faq-response');
          message.insertAdjacentElement(
            'afterend',
            addElement('div', { classList: 'alert alert-success', innerText: response.success })
          );
          form.reset();
        }

        if (response.error) {
          loader.classList.add('d-none');
          const message = document.getElementById('pmf-add-faq-response');
          message.insertAdjacentElement(
            'afterend',
            addElement('div', { classList: 'alert alert-danger', innerText: response.error })
          );
        }
      }
    });
  }
};

export const handleShowFaq = async () => {
  const bookmarkToggle = document.getElementById('pmf-bookmark-toggle');
  if (bookmarkToggle) {
    bookmarkToggle.addEventListener('click', async (event) => {
      event.preventDefault();
      event.stopPropagation();
      if (bookmarkToggle.getAttribute('data-pmf-action') === 'remove') {
        const response = await deleteBookmark(bookmarkToggle.getAttribute('data-pmf-id'));
        if (response.success) {
          pushNotification(response.success);
          document.getElementById('pmf-bookmark-icon').classList.remove('bi-bookmark-fill');
          document.getElementById('pmf-bookmark-icon').classList.add('bi-bookmark');
          bookmarkToggle.innerText = response.linkText;
          bookmarkToggle.setAttribute('data-pmf-action', 'add');
        } else {
          pushErrorNotification(response.error);
        }
      } else {
        const response = await createBookmark(bookmarkToggle.getAttribute('data-pmf-id'));
        if (response.success) {
          pushNotification(response.success);
          document.getElementById('pmf-bookmark-icon').classList.remove('bi-bookmark');
          document.getElementById('pmf-bookmark-icon').classList.add('bi-bookmark-fill');
          bookmarkToggle.innerText = response.linkText;
          bookmarkToggle.setAttribute('data-pmf-action', 'remove');
        } else {
          pushErrorNotification(response.error);
        }
      }
    });
  }
};
