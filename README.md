# Advanced Validator Module for Magento 2

The **Advanced Validator** module for Magento 2 allows you to implement custom validation rules for checkout fields. You can use regular expressions to validate fields like city, postcode, and more during the checkout process.

## Installation

You can install the **Advanced Validator** module by manually adding the files to your Magento project.

### Unpack Files

1. Download the module files.
2. Unpack them into the following directory:
app/code/M2S/AdvancedValidator/

## Enable the Module

After installation, enable the module by running the following commands:

`bin/magento setup:upgrade`

`bin/magento module:enabled M2S_AdvancedValidator`

`bin/magento flush:cache`

---

## Configuration

Once installed and enabled, the **Advanced Validator** module can be configured from the Magento Admin Panel.

1. **Navigate to the Configuration Page**:
    - Go to `Stores > Configuration > M2S > Advanced Validator`.

### Configuration Options

#### 1. **Module Enable** (General Configuration)

- **Field ID**: `enabled`
- **Type**: Select (Yes/No)
- **Default Value**: Yes

**Description**:  
This option enables or disables the module. When set to `Yes`, the custom validation rules defined in the settings will be applied during checkout.

#### 2. **Custom Validation Regex List** (General Configuration)

- **Field ID**: `validation_json_regex`
- **Type**: Grid of Columns (Country codes, Validation Key, Regex, Message)

**Description**:  
This setting allows you to define custom validation rules for specific checkout fields using regular expressions. The columns you will see in the Admin Panel are:

| **Country codes** | **Validation Key**        | **Regex**                   | **Message**                  |
|-------------------|---------------------------|-----------------------------|------------------------------|
| `PL`              | `custom-city-validation`   | `^[a-zA-Z0-9-_]+$`          | Please enter a valid city.   |
| `PL,US`           | `custom-postcode-validation` | `^\d{5,6}$`               | Please enter a valid postcode.|

- **Country codes**: Country codes of the field to validate.
- **Validation Key**: A unique identifier for the validation rule.
- **Regex**: Regular expression used for validation.
- **Message**: Custom error message shown when validation fails.

#### 3. **Fields Validation Rules** (General Configuration)

- **Field ID**: `fields_validation`
- **Type**: Grid of Columns (Field code, Validation Key, Validation Enabled, Form Type)

**Description**:  
This setting maps specific fields to the validation rules defined above. You can assign validation rules to various fields in the checkout process, such as `city` or `postcode`. It also includes options to enable or disable validation for each field and select the form type it applies to (Shipping Address, Billing Address, or All Forms).

| **Field code** | **Validation Key**        | **Validation Enabled** | **Form Type**          |
|----------------|---------------------------|------------------------|------------------------|
| `city`         | `custom-city-validation`   | Yes                    | All forms              |
| `postcode`     | `custom-postcode-validation` | Yes                  | Shipping Address       |

- **Field code**: The checkout field code where the validation rule should be applied.
- **Validation Key**: The validation key defined in the Custom Validation Regex List that should be used for the selected field.
- **Validation Enabled**: A toggle (Yes/No) to enable or disable validation for the field.
- **Form Type**: Defines the form the validation rule applies to: `All forms`, `Shipping Address`, `Billing Address`.

#### 4. **Shipping Address Path** (Advanced Configuration)

- **Field ID**: `shipping_address_path`
- **Type**: Text
- **Default Value**: /shipping-step/children/shippingAddress/children/shipping-address-fieldset/children

**Description**:  
This field allows you to define a custom path for the shipping address. If your checkout flow has a custom structure, specify the path where the shipping address is located.

#### 5. **Billing Address Path** (Advanced Configuration)

- **Field ID**: `billing_address_path`
- **Type**: Text
- **Default Value**: /billing-step/children/payment/children/afterMethods/children/billing-address-form/children/form-fields/children

**Description**:  
Similar to the shipping address path, this field allows you to specify a custom path for the billing address. This is useful if you have a custom address structure.
You need to have the option to display beyond payments enabled for this feature to work.
---

## Example Use Case

### Scenario 1: Custom Validation for City Field

1. In the Custom Validation Regex List, define a rule to accept only alphanumeric characters, hyphens, and underscores, ensuring that each Validation Key is unique. Optionally, you can also define Country Codes (e.g., PL, US) to restrict validation to specific countries.

| **Country codes** | **Validation Key**         | **Regex**                   | **Message**                  |
|-------------------|----------------------------|-----------------------------|------------------------------|
| `PL`               | `custom-city-validation`    | `^[a-zA-Z0-9-_]+$`          | Please enter a valid city.   |

2. In the **Fields Validation Rules**, map the `city` field to the `custom-city-validation` key:

| **Field code** | **Validation Key**         | **Validation Enabled** | **Form Type**          |
|----------------|----------------------------|------------------------|------------------------|
| `city`         | `custom-city-validation`    | Yes                    | All forms              |

---

## Sorting Configuration

The **Sorting Configuration** section allows you to configure the sort order of the fields on the checkout page.

### Example Use Case for Sorting

In this scenario, you have the `city` field set to appear first, followed by the `postcode` field in the checkout forms:

1. In the **Sorting Configuration**, define the sort order for the `city` and `postcode` fields:

| **Field code** | **Sort Order** | **Form Type**          |
|----------------|----------------|------------------------|
| `city`         | `50`           | All forms              |
| `postcode`     | `60`           | Shipping Address       |

2. The `city` field will appear before the `postcode` field, ensuring a consistent and logical order based on your configuration.

---

This sorting configuration allows store admins to control the layout and order of fields in the checkout process, enhancing the user experience.


## Label Configuration

The **Label Configuration** section allows you to define custom labels for checkout fields.

### Example Use Case for Label Configuration

In this scenario, you want to customize the label for the `city` field in the checkout form:

1. In the **Label Configuration**, set the label for the `city` field:

| **Field code** | **Label**         | **Form Type**          |
|----------------|-------------------|------------------------|
| `city`         | `Your City Name`   | All forms              |

This configuration allows store admins to control the text displayed for each field during the checkout process.

---

## Class Configuration

The **Class Configuration** section allows you to specify custom CSS classes for checkout fields.

### Example Use Case for Class Configuration

In this scenario, you want to assign a CSS class to the `city` field:

1. In the **Class Configuration**, set the CSS class for the `city` field:

| **Field code** | **CSS Class** | **Form Type**          |
|----------------|---------------|------------------------|
| `city`         | `col-3`       | All forms              |

This configuration allows store admins to control the appearance of fields by applying custom CSS classes.


## Credits

- Developed by Piotr Wlosek
- [piotr.wlosekx@gmail.com](mailto:piotr.wlosekx@gmail.com)
