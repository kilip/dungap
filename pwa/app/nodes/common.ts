import { useEffect, useState } from "react";
import { Node } from "~/types/node";
import { FetchResponse } from "~/types/response";
import { State } from "~/types/state";
import { fetchApi } from "~/utils/api";
import { useMercure } from "~/utils/mercure";

export function useOnline(
  node: Node,
  hubUrl: string | null
) {
  const [ data, setData ] = useState<State | undefined>(undefined);
  const [ online, setOnline ] = useState(false);
  const topic = `/states/latest/${node.states.online}`;
  const item = useMercure(data, hubUrl, topic);

  useEffect(() => {
    const url = `/states/latest/${node.states.online}`;
    (async () => {

      try {
        const response: FetchResponse<State> | undefined = await fetchApi(url);
        if (response?.data) {
          setData(response.data);
        }
      } catch (error: unknown) {
        console.log(error);
      }
    })();
  }, [ node ]);

  useEffect(() => {
    if (item) {
      setOnline(item.state === 'online');
    }
  }, [ item ]);

  return online;
}
