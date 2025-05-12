import { fileURLToPath } from 'node:url'
import { mergeConfig, defineConfig, configDefaults } from 'vitest/config'
import type { UserConfigFn } from 'vite'
import viteConfig from './vite.config'

const resolvedViteConfig = (viteConfig as UserConfigFn)({
  mode: 'test',
  command: 'serve'
})

export default mergeConfig(
  resolvedViteConfig,
  defineConfig({
    test: {
      environment: 'jsdom',
      exclude: [...configDefaults.exclude, 'e2e/**'],
      root: fileURLToPath(new URL('./', import.meta.url))
    }
  })
)