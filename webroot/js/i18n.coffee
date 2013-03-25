global = Function('return this')()

__ = (msgid)->
  if global.po?[msgid]
    return global.po[msgid]
  return msgid

global.__ = __