import { Box, Text, TextField } from "@radix-ui/themes";

interface Props {
  name: string;
  label: string;
  defaultValue?: string;
  disabled?: boolean;
}

export default function TextInput({
  name,
  label,
  defaultValue = "",
  disabled = false,
}: Props) {
  return (
    <Box minWidth="300px" className="cursor-pointer">
      <Text
        as="label"
        htmlFor={name}
        size="3"
        weight="bold"
        className="cursor-pointer"
      >
        {label}
      </Text>
      <TextField.Root
        id={name}
        name={name}
        variant="surface"
        radius="medium"
        defaultValue={defaultValue ?? ""}
        disabled={disabled}
      />
    </Box>
  );
}
