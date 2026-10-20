const enqueuedHandles = {};

export async function enqueueScript(scriptData, dependencies, handle) {
  if (typeof scriptData === 'undefined' || typeof scriptData.handle === 'undefined') {
    // eslint-disable-next-line no-console
    console.info('Undefined Script', handle);
    return false;
  }

  if (typeof enqueuedHandles[scriptData.handle] !== 'undefined') {
    return enqueuedHandles[scriptData.handle];
  }

  if (document.querySelector(`#${scriptData.handle}-js, #${scriptData.handle}-js-after`)) {
    return false;
  }

  enqueuedHandles[scriptData.handle] = new Promise(async (resolve) => {
    await enqueueDependencies(scriptData.dependencies, dependencies);

    if (scriptData.extra) {
      const extraScript = document.createElement('script');
      extraScript.textContent = scriptData.extra;
      extraScript.id = `${scriptData.handle}-js-extra`;
      document.head.appendChild(extraScript);
    }

    if (scriptData.before) {
      const beforeScript = document.createElement('script');
      beforeScript.textContent = scriptData.before;
      beforeScript.id = `${scriptData.handle}-js-before`;
      document.head.appendChild(beforeScript);
    }

    if (scriptData.src) {
      const script = document.createElement('script');

      script.src = scriptData.src;
      script.id = `${scriptData.handle}-js`;
      script.onload = function () {
        if (scriptData.after) {
          const afterScript = document.createElement('script');
          afterScript.textContent = scriptData.after;
          afterScript.id = `${scriptData.handle}-js-after`;
          document.head.appendChild(afterScript);
        }
        resolve();
      };

      document.head.appendChild(script);
    } else {
      const afterScript = document.createElement('script');
      afterScript.textContent = scriptData.after;
      afterScript.id = `${scriptData.handle}-js-after`;
      document.head.appendChild(afterScript);

      resolve();
    }
  });

  return enqueuedHandles[scriptData.handle];
}

export async function enqueueDependencies(dependencyHandles, dependencies) {
  for (const i in dependencyHandles) {
    const dependencyHandle = dependencyHandles[i];
    await enqueueScript(dependencies[dependencyHandle], dependencies);
  }
}
