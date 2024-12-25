# Advanced Validator Module for Magento 2

The **Advanced Validator** module for Magento 2 allows you to implement custom validation rules for checkout fields. You can use regular expressions to validate fields like city, postcode, and more during the checkout process.

## Installation

You can install the **Advanced Validator** module using either Composer or by manually adding the files to your Magento project.

### Option 1: Unpack Files

1. Download the module files.
2. Unpack them into the following directory:

### Option 2: Install via Composer

Run the following command in your Magento root directory:

`composer require m2s/advanced-restriction`

## Enable the Module

After installation, enable the module by running the following commands:

`bin/magento module:enabled M2S_AdvancedValidator`

`bin/magento setup:upgrade`

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

## Credits

- Developed by Piotr Wlosek
- [piotr.wlosekx@gmail.com](mailto:piotr.wlosekx@gmail.com)
