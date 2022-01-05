const deleteBtn = document.querySelector('#delete-btn');

// https://www.geeksforgeeks.org/how-to-display-confirmation-dialog-when-clicking-an-a-link-using-javascript-jquery/
deleteBtn.addEventListener(
  'click',
  (e) => {
    if (!confirm('Are you sure?')) e.preventDefault();
  },
  false
);
