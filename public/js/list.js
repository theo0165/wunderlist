const taskContainers = document.querySelectorAll('.task-container');

taskContainers.forEach((task) => {
  const checkbox = task.querySelector("input[type='checkbox']");
  const taskId = task.querySelector("input[name='uuid']").value;
  const form = task.querySelector('form.complete-form');

  console.log(form);

  checkbox.addEventListener('change', () => {
    console.log('Checkbox changed', checkbox.checked, taskId);

    fetch('/task/' + taskId + '/edit', {
      method: 'POST',
      body: new FormData(form),
    }).then((response) => {
      if (response.ok) {
        task.classList.toggle('completed');
      }
    });
  });
});
