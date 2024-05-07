import { useEffect, useState } from "react";
import { PagedCollection, isPagedCollection } from "~/types/collection";
import { Item, isItem } from "~/types/item";

export const mercureSubscribe = <T extends Item | PagedCollection<Item> | null | undefined>(
  hubURL: string,
  data: T | PagedCollection<T>,
  setData: (data: T) => void,
  topic: string | undefined = undefined
) => {
  if (!data || !data[ "@id" ]) throw new Error("@id is missing");

  const url = new URL(hubURL, window.origin);
  const id = topic ?? data[ '@id' ];
  url.searchParams.append(
    "topic",
    new URL(id, window.origin).toString()
  );
  const eventSource = new EventSource(url.toString());
  console.log(url.toString());
  eventSource.addEventListener("message", (event) =>
    setData(JSON.parse(event.data))
  );

  return eventSource;
};

export function useMercure<TData extends Item | PagedCollection<Item> | null | undefined>(
  deps: TData,
  hubURL: string | null,
  topic: string | undefined = undefined
): TData {
  const [ data, setData ] = useState(deps);

  useEffect(() => {
    setData(deps);
  }, [ deps ]);

  useEffect(() => {
    if (null === hubURL || !data) {
      return;
    }

    if (!isPagedCollection<Item>(data) && !isItem(data)) {
      console.error('Object sent is not in JSON-LD format.');
      console.error(data);
    }

    if (
      isPagedCollection<Item>(data)
      && data[ 'hydra:member' ]
      && data[ 'hydra:member' ].length !== 0
    ) {
      const eventSources: EventSource[] = [];
      data[ 'hydra:member' ].forEach((obj, pos) => {
        eventSources.push(
          mercureSubscribe(hubURL, obj, (datum) => {
            if (data[ 'hydra:member' ]) {
              data[ 'hydra:member' ][ pos ] = datum;
            }
            setData({ ...data });
          })
        );
      });

      return () => {
        eventSources.forEach((eventSource) => eventSource.close());
      };
    }

    if (isItem(data)) {
      // it's a single object
      const eventSource = mercureSubscribe<TData>(hubURL, data, setData, topic);

      return () => {
        eventSource.close();
      };
    }
  }, [ data, hubURL ]);

  return data;
}
