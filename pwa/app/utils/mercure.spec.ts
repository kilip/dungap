import { renderHook } from '@testing-library/react';
import { describe, expect, it } from "vitest";
import { PagedCollection } from "~/types/collection";
import { Item } from "~/types/item";
import { mercureSubscribe, useMercure } from "./mercure";

const { MockEvent, EventSource } = require('mocksse');

global.EventSource = EventSource;

window.origin = 'https://localhost/dashboard';
const hubURL = 'https://localhost/mercure';
const setData = (item: Item) => {
};
describe('mercureSubscribe()', () => {

  it('should throws exception when @id not exists', () => {
    const item = {
      id: 'foo'
    } as Item;

    expect(() => {
      const evt = mercureSubscribe(hubURL, item, setData);
    }).toThrow(/^@id is missing/);

  });

  it('should configure EventSource', () => {
    const item: Item = {
      '@id': 'id',
      id: 'id'
    };

    const evt = mercureSubscribe<Item>(hubURL, item, setData);
    expect(evt).toBeDefined();
  });
});

describe('useMercure', () => {
  it('should configure item EventSource', () => {
    const item: Item = {
      '@id': 'id',
      id: 'id'
    };

    const { result } = renderHook(() => useMercure(item, hubURL));
    expect(result).toBeDefined();
    expect(result.current).toBe(item);
  });

  it('should configure PagedCollection<Item> EventSource', () => {
    const item: Item = {
      '@id': 'id',
      id: 'id'
    };

    const paged = {
      [ 'hydra:member' ]: [ item ]
    } as PagedCollection<Item>;

    const { result } = renderHook(() => useMercure(paged, hubURL));
    expect(result).toBeDefined();
    expect(result.current).toBe(paged);
  });
});