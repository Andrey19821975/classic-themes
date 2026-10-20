/* eslint-disable guard-for-in,no-restricted-syntax */
import { enqueueScript } from './scripts.js';

window.addEventListener('load', () => {
  const script = document.querySelector('#theme-delay-scripts-js');
  const delayScriptsByTimeout = JSON.parse(script.dataset.scriptsByTimeout);
  const delayScriptsByActivity = JSON.parse(script.dataset.scriptsByActivity);
  const delayScriptsByView = JSON.parse(script.dataset.scriptsByView);
  const dependencies = JSON.parse(script.dataset.dependencies);

  if (delayScriptsByTimeout) {
    for (const timeout in delayScriptsByTimeout) {
      setTimeout(async () => {
        const delayScripts = delayScriptsByTimeout[timeout];
        for (const delayScriptHandle in delayScripts) {
          const delayScript = delayScripts[delayScriptHandle];
          await enqueueScript(delayScript, dependencies, delayScriptHandle);
        }
      }, parseInt(timeout, 10));
    }
  }

  if (delayScriptsByActivity) {
    for (const timeout in delayScriptsByActivity) {
      // eslint-disable-next-line no-undef
      themeUserActiveAction(async () => {
        const delayScripts = delayScriptsByActivity[timeout];
        for (const delayScriptHandle in delayScripts) {
          const delayScript = delayScripts[delayScriptHandle];
          await enqueueScript(delayScript, dependencies, delayScriptHandle);
        }
      }, parseInt(timeout, 10));
    }
  }

  if (delayScriptsByView) {
    for (const timeout in delayScriptsByView) {
      const delayScripts = delayScriptsByView[timeout];
      for (const delayScriptHandle in delayScripts) {
        const delayScript = delayScripts[delayScriptHandle];

        // eslint-disable-next-line no-undef
        themeViewAction(
          async () => {
            await enqueueScript(delayScript, dependencies, delayScriptHandle);
          },
          Object.values(delayScript.selector).join(','),
          parseInt(timeout, 10),
        );
      }
    }
  }
});
