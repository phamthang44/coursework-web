function Validator(options) {
  function getParent(element, selector) {
    while (element.parentElement) {
      if (element.parentElement.matches(selector)) {
        return element.parentElement;
      }
      element = element.parentElement; //VIP
    }
    //console.log("getParent: element or parentElement is null", element);
    return null;
  }

  let selectorRules = {};

  function validate(inputElement, rule) {
    let errorMessage;
    //console.log(inputElement.parentElement);
    let errorElement = getParent(
      inputElement,
      options.formGroupSelector
    ).querySelector(options.formMessage);

    let rules = selectorRules[rule.selector];

    for (let i = 0; i < rules.length; i++) {
      switch (inputElement.type) {
        case "checkbox":
          errorMessage = rules[i](
            formElement.querySelector(rule.selector + ":checked")
          );
          break;
        case "radio":
          errorMessage = rules[i](
            formElement.querySelector(rule.selector + ":checked")
          );
          break;
        case "textarea":
          errorMessage = rules[i](inputElement.value);
          break;
        default:
          errorMessage = rules[i](inputElement.value);
      }

      if (errorMessage) break;
    }

    if (errorMessage) {
      errorElement.innerText = errorMessage;
      getParent(inputElement, options.formGroupSelector).classList.add(
        "invalid"
      );
    } else {
      errorElement.innerText = "";
      getParent(inputElement, options.formGroupSelector).classList.remove(
        "invalid"
      );
    }

    return !errorMessage;
  }

  let formElement = document.querySelector(options.form);

  if (formElement) {
    formElement.onsubmit = function (e) {
      e.preventDefault();

      let isFormValid = true;

      options.rules.forEach((rule) => {
        //console.log(formElement.querySelector(rule.selector));
        let inputElement = formElement.querySelector(rule.selector);
        //please ensure the inputElement is not null in the file which is form because when throw an undefined selector will get error
        validate(inputElement, rule);
        let isValid = validate(inputElement, rule);

        if (!isValid) {
          isFormValid = false;
        }
      });

      if (isFormValid) {
        if (typeof options.onSubmit === "function") {
          let enableInputs = formElement.querySelectorAll(
            "[name]:not([disabled])"
          );
          //console.log(enableInputs); //NodeList

          let formValues = Array.from(enableInputs).reduce((values, input) => {
            switch (input.type) {
              case "radio":
                values[input.name] = formElement.querySelector(
                  'input[name="' + input.name + '"]:checked'
                ).value;
                break;
              case "checkbox":
                if (!input.matches(":checked")) return (values = "");
                if (!Array.isArray(values[input.name])) {
                  values[input.name] = [];
                }
                values[input.name].push(input.value);
                break;
              case "file":
                values[input.name] = input.files;
                break;
              default:
                values[input.name] = input.value;
            }

            return values;
          }, {});

          options.onSubmit(formValues);
        } else {
          formElement.submit();
        }
      }
    };

    options.rules.forEach((rule) => {
      let inputElements = formElement.querySelectorAll(rule.selector);

      Array.from(inputElements).forEach(function (inputElement) {
        inputElement.onblur = function () {
          validate(inputElement, rule);
        };

        inputElement.oninput = function () {
          let errorElement = getParent(
            inputElement,
            options.formGroupSelector
          ).querySelector(options.formMessage);
          errorElement.innerText = "";
          getParent(inputElement, options.formGroupSelector).classList.remove(
            "invalid"
          );
        };
      });

      if (Array.isArray(selectorRules[rule.selector])) {
        selectorRules[rule.selector].push(rule.test);
      } else {
        selectorRules[rule.selector] = [rule.test];
      }
    });
  }
}

Validator.isRequired = function (selector) {
  return {
    selector: selector,
    test: function (value) {
      return value ? undefined : "Please input this field";
    },
  };
};

Validator.isRequiredSelection = function (selector) {
  return {
    selector: selector,
    test: function (value) {
      return value ? undefined : "Please select one of these options";
    },
  };
};

Validator.isEmail = function (selector, message) {
  return {
    selector: selector,
    test: function (value) {
      let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      return regex.test(value)
        ? undefined
        : message || "This field must be an email";
    },
  };
};

Validator.minLength = function (selector, min, message) {
  return {
    selector: selector,
    test: function (value) {
      return value.length >= min
        ? undefined
        : message || `Please input at least ${min} characters`;
    },
  };
};

Validator.isConfirmed = function (selector, getConfirmedValue, message) {
  return {
    selector: selector,
    test: function (value) {
      return value === getConfirmedValue()
        ? undefined
        : message || "The confirmation does not match with the password!";
    },
  };
};

Validator.isRequiredDate = function (selector) {
  return {
    selector: selector,
    test: function () {},
  };
};

Validator.hasNoWhiteSpace = function (selector) {
  return {
    selector: selector,
    test: function (s) {
      return !/\s/g.test(s) ? undefined : "Please do not use white space";
    },
  };
};
