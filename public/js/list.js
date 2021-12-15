const taskForms = document.querySelectorAll('form.task-container');

taskForms.forEach((task) => {
  const checkbox = task.querySelector("input[type='checkbox']");
  const taskId = task.querySelector("input[name='uuid']").value;

  checkbox.addEventListener('change', () => {
    console.log('Checkbox changed', checkbox.checked, taskId);

    fetch('/task/' + taskId + '/edit', {
      method: 'POST',
      body: new FormData(task),
    });
  });
});
