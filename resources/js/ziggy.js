const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"products.index":{"uri":"products","methods":["GET","HEAD"]},"products.store":{"uri":"products\/store","methods":["POST"]},"products.update":{"uri":"products\/update\/{id}","methods":["PUT"],"parameters":["id"]},"products.delete":{"uri":"products\/delete\/{id}","methods":["DELETE"],"parameters":["id"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
